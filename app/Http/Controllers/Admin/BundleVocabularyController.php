<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BundleVocabularySet;
use App\Models\BundleVocabularyWord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BundleVocabularyController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        /** @var User $authUser */

        $this->authorizeApprover($authUser);

        $status = $request->get('status');
        $search = trim((string) $request->get('search'));

        $setsQuery = BundleVocabularySet::query()
            ->with(['bundle:id,slug,creator_id,teacher_id', 'creator:id,full_name,email', 'approver:id,full_name'])
            ->withCount('words');

        if (in_array($status, BundleVocabularySet::$statuses, true)) {
            $setsQuery->where('status', $status);
        }

        if ($search !== '') {
            $setsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('bundle', function ($bundleQuery) use ($search) {
                        $bundleQuery->where('slug', 'like', "%{$search}%");
                    })
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('full_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $sets = $setsQuery
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('submitted_at')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $pendingCount = BundleVocabularySet::query()
            ->where('status', BundleVocabularySet::STATUS_PENDING)
            ->count();

        return view('admin.dictionary.bundle_vocabulary.index', [
            'pageTitle' => trans('panel.bundle_vocabulary_library'),
            'sets' => $sets,
            'status' => $status,
            'search' => $search,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function show($id)
    {
        $authUser = Auth::user();
        /** @var User $authUser */

        $this->authorizeApprover($authUser);

        $set = BundleVocabularySet::query()
            ->with(['bundle:id,slug,creator_id,teacher_id', 'creator:id,full_name,email', 'approver:id,full_name'])
            ->findOrFail($id);

        $words = BundleVocabularyWord::query()
            ->where('vocabulary_set_id', $set->id)
            ->orderBy('sort_order')
            ->paginate(100);

        return view('admin.dictionary.bundle_vocabulary.show', [
            'pageTitle' => trans('panel.bundle_vocabulary_set_detail'),
            'set' => $set,
            'words' => $words,
        ]);
    }

    public function update(Request $request, $id)
    {
        $authUser = Auth::user();
        /** @var User $authUser */

        $this->authorizeApprover($authUser);

        $set = BundleVocabularySet::query()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'original_price' => 'nullable|numeric|min:0.01|max:9999999999.99',
            'sale_price' => 'nullable|numeric|min:0|max:9999999999.99|lte:original_price',
            'currency_code' => 'required|string|in:VND,USD',
            'is_published_global' => 'nullable|boolean',
            'source_file' => 'nullable|file|mimes:csv,txt,xlsx,xls|max:20480',
        ]);

        if ($request->boolean('is_published_global')
            && (!$request->filled('original_price') || !$request->filled('sale_price'))
        ) {
            return back()->withErrors([
                'original_price' => trans('panel.bundle_vocabulary_pricing_required_for_publish'),
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $set->name = $request->name;
            $set->description = $request->description;
            $set->original_price = $request->filled('original_price') ? $request->original_price : null;
            $set->sale_price = $request->filled('sale_price') ? $request->sale_price : null;
            $set->currency_code = $request->currency_code;
            $set->is_published_global = (bool) $request->boolean('is_published_global');

            if ($request->hasFile('source_file')) {
                $parsedWords = $this->parseUploadedVocabularyFile($request->file('source_file'));

                if (empty($parsedWords)) {
                    return back()->withErrors([
                        'source_file' => trans('panel.bundle_vocabulary_no_valid_words'),
                    ])->withInput();
                }

                if (!empty($set->source_file_path)) {
                    Storage::disk('public')->delete($set->source_file_path);
                }

                $set->source_file_path = $request->file('source_file')->store('bundle-vocabulary', 'public');
                $set->save();

                $this->replaceVocabularySetWords($set, $parsedWords);
            } else {
                $set->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Admin bundle vocabulary set update failed', [
                'set_id' => $id,
                'user_id' => $authUser->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'source_file' => trans('panel.bundle_vocabulary_update_failed'),
            ])->withInput();
        }

        return back()->with('toast', [
            'title' => trans('public.request_success'),
            'msg' => trans('panel.bundle_vocabulary_updated'),
            'status' => 'success',
        ]);
    }

    public function approve($id)
    {
        $authUser = Auth::user();
        /** @var User $authUser */

        $this->authorizeApprover($authUser);

        $set = BundleVocabularySet::query()->findOrFail($id);

        if ((int) $set->words_count < 1) {
            return back()->withErrors([
                'approve' => trans('panel.bundle_vocabulary_submit_requires_words'),
            ]);
        }

        $set->status = BundleVocabularySet::STATUS_APPROVED;
        $set->approved_by = $authUser->id;
        $set->approved_at = now();
        $set->rejected_at = null;
        $set->rejection_note = null;
        $set->save();

        return back()->with('toast', [
            'title' => trans('public.request_success'),
            'msg' => trans('panel.bundle_vocabulary_approved'),
            'status' => 'success',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $authUser = Auth::user();
        /** @var User $authUser */

        $this->authorizeApprover($authUser);

        $request->validate([
            'rejection_note' => 'required|string|min:3|max:2000',
        ]);

        $set = BundleVocabularySet::query()->findOrFail($id);

        $set->status = BundleVocabularySet::STATUS_REJECTED;
        $set->approved_by = $authUser->id;
        $set->approved_at = null;
        $set->rejected_at = now();
        $set->rejection_note = $request->rejection_note;
        $set->save();

        return back()->with('toast', [
            'title' => trans('public.request_success'),
            'msg' => trans('panel.bundle_vocabulary_rejected'),
            'status' => 'success',
        ]);
    }

    private function authorizeApprover(User $authUser): void
    {
        if (!$authUser->canApproveBundleVocabulary()) {
            abort(403);
        }
    }

    private function parseUploadedVocabularyFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if (in_array($extension, ['csv', 'txt'], true)) {
            $handle = fopen($file->getRealPath(), 'r');

            if ($handle !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = $row;
                }

                fclose($handle);
            }
        } else {
            $sheets = Excel::toArray([], $file);
            $rows = $sheets[0] ?? [];
        }

        if (empty($rows)) {
            return [];
        }

        [$columnMap, $startIndex] = $this->resolveVocabularyColumnMap($rows);

        $parsed = [];
        $sortOrder = 1;

        for ($index = $startIndex; $index < count($rows); $index++) {
            $row = $rows[$index];

            if (!is_array($row)) {
                continue;
            }

            $word = $this->cleanCellValue($row[$columnMap['word']] ?? null);

            if (empty($word)) {
                continue;
            }

            $definition = $this->cleanCellValue($row[$columnMap['definition']] ?? null);
            $translation = $this->cleanCellValue($row[$columnMap['translation']] ?? null);

            if (empty($translation)) {
                $translation = $this->translateToVietnamese($word);
            }

            $imageUrl = $this->cleanCellValue($row[$columnMap['image_url']] ?? null);
            if (empty($imageUrl)) {
                $imageUrl = $this->buildVocabularyIllustrationUrl($word);
            }

            $parsed[] = [
                'word' => $word,
                'part_of_speech' => $this->cleanCellValue($row[$columnMap['part_of_speech']] ?? null),
                'pronunciation' => $this->cleanCellValue($row[$columnMap['pronunciation']] ?? null),
                'definition' => $definition,
                'translation_vi' => $translation,
                'example' => $this->cleanCellValue($row[$columnMap['example']] ?? null),
                'image_url' => $imageUrl,
                'sort_order' => $sortOrder,
            ];

            $sortOrder++;
        }

        return $parsed;
    }

    private function resolveVocabularyColumnMap(array $rows): array
    {
        $defaultMap = [
            'word' => 0,
            'definition' => 1,
            'translation' => 2,
            'example' => 3,
            'part_of_speech' => 4,
            'pronunciation' => 5,
            'image_url' => 6,
        ];

        $header = $rows[0] ?? [];
        if (!is_array($header)) {
            return [$defaultMap, 0];
        }

        $normalizedHeader = array_map(function ($value) {
            return strtolower(trim((string) $value));
        }, $header);

        $aliases = [
            'word' => ['word', 'term', 'vocabulary'],
            'definition' => ['definition', 'meaning', 'explanation'],
            'translation' => ['translation', 'translation_vi', 'vietnamese', 'vi', 'nghia'],
            'example' => ['example', 'sample', 'example_sentence'],
            'part_of_speech' => ['part_of_speech', 'part of speech', 'pos', 'word_type'],
            'pronunciation' => ['pronunciation', 'ipa', 'phonetic'],
            'image_url' => ['image', 'image_url', 'image link', 'illustration'],
        ];

        $resolvedMap = $defaultMap;
        $hasHeader = false;

        foreach ($aliases as $key => $possibleNames) {
            foreach ($possibleNames as $name) {
                $columnIndex = array_search($name, $normalizedHeader, true);

                if ($columnIndex !== false) {
                    $resolvedMap[$key] = $columnIndex;

                    if ($key === 'word') {
                        $hasHeader = true;
                    }

                    break;
                }
            }
        }

        return [$resolvedMap, $hasHeader ? 1 : 0];
    }

    private function cleanCellValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim(strip_tags((string) $value));

        return $cleaned === '' ? null : $cleaned;
    }

    private function translateToVietnamese(string $text): string
    {
        static $translator = null;
        static $cache = [];

        $cacheKey = mb_strtolower(trim($text));
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $translated = $text;

        try {
            if ($translator === null) {
                $translator = new GoogleTranslate();
                $translator->setSource('en');
                $translator->setTarget('vi');
            }

            $result = trim((string) $translator->translate($text));
            if (!empty($result)) {
                $translated = $result;
            }
        } catch (\Throwable $e) {
            Log::warning('Admin vocabulary translation fallback used', [
                'text' => $text,
                'message' => $e->getMessage(),
            ]);
        }

        $cache[$cacheKey] = $translated;

        return $translated;
    }

    private function buildVocabularyIllustrationUrl(string $word): string
    {
        $safeWord = urlencode(mb_strtolower(trim($word)));

        return "https://loremflickr.com/640/420/{$safeWord}?lock=" . abs(crc32($word));
    }

    private function replaceVocabularySetWords(BundleVocabularySet $set, array $parsedWords): void
    {
        BundleVocabularyWord::query()->where('vocabulary_set_id', $set->id)->delete();

        $now = now();
        $rows = [];

        foreach ($parsedWords as $word) {
            $rows[] = [
                'vocabulary_set_id' => $set->id,
                'word' => $word['word'],
                'part_of_speech' => $word['part_of_speech'] ?? null,
                'pronunciation' => $word['pronunciation'] ?? null,
                'definition' => $word['definition'] ?? null,
                'translation_vi' => $word['translation_vi'] ?? null,
                'example' => $word['example'] ?? null,
                'image_url' => $word['image_url'] ?? null,
                'sort_order' => $word['sort_order'] ?? 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            BundleVocabularyWord::query()->insert($chunk);
        }

        $set->words_count = count($rows);
        $set->save();
    }
}