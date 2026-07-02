<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Flashcard;
use App\Models\WordList;
use App\Models\UserWordProgress;
use App\Models\AcademicWordList;
use App\Models\AcademicWordListWord;
use App\Models\Bundle;
use App\Models\BundleVocabularySet;
use App\Models\BundleVocabularyWord;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Stichoza\GoogleTranslate\GoogleTranslate;

class DictionaryController extends Controller
{
    // Using Free Dictionary API - free, no key required, has UK + US pronunciation audio
    private $freeDictBaseUrl = 'https://api.dictionaryapi.dev/api/v2/entries/en';

    public function publicIndex()
    {
        $publishedBundleVocabularySets = BundleVocabularySet::query()
            ->with(['bundle'])
            ->publishedForDictionary()
            ->where('words_count', '>', 0)
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (BundleVocabularySet $set) {
                $originalPrice = (float) $set->original_price;
                $salePrice = (float) $set->sale_price;
                $currencyCode = in_array($set->currency_code, BundleVocabularySet::$currencies, true)
                    ? $set->currency_code
                    : BundleVocabularySet::CURRENCY_VND;

                $discountPercent = 0;
                if ($originalPrice > 0 && $salePrice <= $originalPrice) {
                    $discountPercent = (int) round((($originalPrice - $salePrice) / $originalPrice) * 100);
                }

                $bundleSlug = !empty($set->bundle) ? $set->bundle->slug : null;

                return [
                    'id' => $set->id,
                    'set_name' => $set->name,
                    'set_description' => $set->description,
                    'intro_content' => $set->intro_content,
                    'feature_content' => $set->feature_content,
                    'bundle_slug' => $bundleSlug,
                    'word_count' => (int) $set->words_count,
                    'original_price' => $originalPrice,
                    'sale_price' => $salePrice,
                    'currency_code' => $currencyCode,
                    'original_price_label' => number_format($originalPrice, 0, '.', ',') . ' ' . $currencyCode,
                    'sale_price_label' => number_format($salePrice, 0, '.', ',') . ' ' . $currencyCode,
                    'discount_percent' => $discountPercent,
                ];
            })
            ->values();

        return view('design_1.web.dictionary.index', [
            'publishedBundleVocabularySets' => $publishedBundleVocabularySets,
        ]);
    }

    public function publicPreviewBundleVocabularySet($id)
    {
        $set = BundleVocabularySet::query()
            ->publishedForDictionary()
            ->find($id);

        if (!$set) {
            return response()->json([
                'success' => false,
                'message' => 'Word List package not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $set->id,
                'name' => $set->name,
                'word_count' => (int) $set->words_count,
                'description' => $set->description,
                'intro_content' => $set->intro_content,
                'feature_content' => $set->feature_content,
            ],
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        /** @var User $user */

        // Get Academic Word Lists (Band-based) that user has access to
        $academicWordLists = AcademicWordList::with(['words', 'accessUsers'])
            ->where('is_active', true)
            ->get()
            ->map(function($list) use ($user) {
                $hasAccess = $list->hasAccess($user->id);
                return [
                    'id' => 'academic_' . $list->id,
                    'source_id' => $list->id,
                    'source_type' => 'academic',
                    'name' => $list->name,
                    'description' => $list->description,
                    'band_level' => $list->band_level,
                    'word_count' => $list->word_count,
                    'has_access' => $hasAccess,
                    'is_locked' => !$hasAccess,
                ];
            });

        $bundleAcademicWordLists = $this->getApprovedBundleVocabularySetsForUser($user)
            ->map(function (BundleVocabularySet $set) {
                $bundleTitle = !empty($set->bundle) ? ($set->bundle->title ?: $set->bundle->slug) : ('Bundle #' . $set->bundle_id);

                return [
                    'id' => 'bundle_' . $set->id,
                    'source_id' => $set->id,
                    'source_type' => 'bundle',
                    'name' => $set->name,
                    'description' => $bundleTitle . ' - ' . ($set->description ?: 'Bundle vocabulary set'),
                    'band_level' => 'Bundle',
                    'word_count' => (int) $set->words_count,
                    'has_access' => true,
                    'is_locked' => false,
                ];
            });

        $academicWordLists = $academicWordLists->concat($bundleAcademicWordLists)->values();

        // My Word List only contains user-saved words from dictionary search
        $myWordList = $this->getOrCreatePersonalWordList($user);

        // Get user stats
        $userStats = [
            'streak' => $this->getUserStreak($user->id),
            'ranking' => 0, // TODO: Implement ranking logic
            'band_estimate' => $user->band_estimate ?? 5.0,
        ];

        $canManageBundleVocabulary = $user->canManageBundleVocabulary();
        $bundleVocabularyPendingCount = 0;

        if ($user->canApproveBundleVocabulary()) {
            $bundleVocabularyPendingCount = BundleVocabularySet::query()
                ->where('status', BundleVocabularySet::STATUS_PENDING)
                ->count();
        }

        $data = [
            'pageTitle' => trans('panel.dictionary_and_flashcard'),
            'hasApiKey' => true, // Free Dictionary API – no key required
            'academicWordLists' => $academicWordLists,
            'myWordList' => $myWordList,
            'userStats' => $userStats,
            'authUser' => $user,
            'canManageBundleVocabulary' => $canManageBundleVocabulary,
            'bundleVocabularyPendingCount' => $bundleVocabularyPendingCount,
        ];

        return view('design_1.panel.dictionary.index_new', $data);
    }

    private function getUserStreak($userId)
    {
        // Calculate consecutive days of practice
        $recentPractices = UserWordProgress::where('user_id', $userId)
            ->whereNotNull('last_practiced_at')
            ->orderBy('last_practiced_at', 'desc')
            ->get()
            ->groupBy(function($item) {
                return $item->last_practiced_at->format('Y-m-d');
            });

        $streak = 0;
        $currentDate = now()->startOfDay();
        
        foreach ($recentPractices as $date => $practices) {
            $practiceDate = \Carbon\Carbon::parse($date);
            $daysDiff = $currentDate->diffInDays($practiceDate, false);
            
            if ($daysDiff == $streak) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get available dictionaries
     * (Cambridge removed – returns a static list)
     */
    public function getDictionaries()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['code' => 'english', 'name' => 'English (Free Dictionary API)']
            ]
        ]);
    }

    /**
     * Search for words
     */
    public function search(Request $request)
    {
        $dictionaryCode = $request->get('dictionary', 'english-vietnamese');
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        $pageSize = $request->get('pageSize', 10);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query is required'
            ], 400);
        }

        // Delegate to searchFirst which uses Free Dictionary API
        return $this->searchFirst(new Request(['query' => $query]));
    }

    /**
     * Get best matching entry using Free Dictionary API
     * Free, unlimited, supports UK + US pronunciation audio
     * Source: https://api.dictionaryapi.dev
     */
    public function searchFirst(Request $request)
    {
        // Accept both 'query', 'word', and 'q' parameters
        $query = strtolower(trim($request->input('query', $request->input('word', $request->get('q', '')))));

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query is required'
            ], 400);
        }

        try {
            $url = $this->freeDictBaseUrl . '/' . urlencode($query);

            $response = Http::timeout(10)->get($url);

            Log::info('Free Dictionary API Request', [
                'url'   => $url,
                'query' => $query,
                'status' => $response->status(),
            ]);

            if ($response->status() === 404) {
                return response()->json([
                    'success' => false,
                    'message' => 'No definition found for "' . $query . '"',
                    'data'    => null
                ]);
            }

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dictionary API request failed (HTTP ' . $response->status() . ')',
                ], 500);
            }

            $apiData = $response->json();

            if (empty($apiData) || !is_array($apiData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No definition found for "' . $query . '"',
                    'data'    => null
                ]);
            }

            // Merge all entries for the same word (different parts of speech come as separate items)
            $transformedData = $this->transformDictionaryData($apiData[0], $apiData);

            return response()->json([
                'success' => true,
                'data'    => $transformedData
            ]);

        } catch (\Exception $e) {
            Log::error('Dictionary API error', [
                'query'   => $query,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transform Cambridge Dictionary API data to our format
     */
    private function transformCambridgeData($data)
    {
        // Extract the first entry if available
        $entry = $data['entryContent'] ?? $data;
        
        $transformed = [
            'word' => $entry['headword'] ?? ($data['headword'] ?? ''),
            'phonetics' => [],
            'meanings' => []
        ];

        // Transform pronunciations from Cambridge API
        if (isset($entry['pronunciations'])) {
            foreach ($entry['pronunciations'] as $pron) {
                $phoneticEntry = [
                    'text' => $pron['ipa'] ?? '',
                    'audio' => ''
                ];
                
                // Check for audio files
                if (isset($pron['audio']) && is_array($pron['audio'])) {
                    foreach ($pron['audio'] as $audioItem) {
                        if (isset($audioItem['url'])) {
                            $phoneticEntry['audio'] = $audioItem['url'];
                            break;
                        }
                    }
                } elseif (isset($pron['audioUrl'])) {
                    $phoneticEntry['audio'] = $pron['audioUrl'];
                }
                
                // Add region label if available
                if (isset($pron['region'])) {
                    $phoneticEntry['text'] = '/' . $phoneticEntry['text'] . '/ ' . $pron['region'];
                }
                
                $transformed['phonetics'][] = $phoneticEntry;
            }
        }

        // Transform senses (definitions) from Cambridge API
        if (isset($entry['senses'])) {
            $meaningsByPos = [];
            
            foreach ($entry['senses'] as $sense) {
                $pos = $sense['partOfSpeech'] ?? $sense['pos'] ?? 'other';
                
                if (!isset($meaningsByPos[$pos])) {
                    $meaningsByPos[$pos] = [
                        'partOfSpeech' => $pos,
                        'definitions' => []
                    ];
                }
                
                $definition = [
                    'definition' => $sense['definition'] ?? ($sense['guideWord'] ?? ''),
                ];
                
                // Add examples
                if (isset($sense['examples']) && is_array($sense['examples'])) {
                    $definition['example'] = $sense['examples'][0]['text'] ?? '';
                } elseif (isset($sense['example'])) {
                    $definition['example'] = $sense['example'];
                }
                
                $meaningsByPos[$pos]['definitions'][] = $definition;
            }
            
            $transformed['meanings'] = array_values($meaningsByPos);
        }

        return $transformed;
    }

    /**
     * Transform Free Dictionary API data to our format.
     * $data = first entry, $allEntries = full array for merging pronunciations across entries.
     */
    private function transformDictionaryData($data, array $allEntries = [])
    {
        $transformed = [
            'headword'      => $data['word'] ?? '',
            'pronunciations' => [],
            'meanings'      => [],
            'synonyms'      => [],
            'antonyms'      => [],
        ];

        // Collect phonetics from ALL returned entries to maximise UK + US coverage
        $phoneticsPool = [];
        foreach ($allEntries as $entry) {
            if (!empty($entry['phonetics']) && is_array($entry['phonetics'])) {
                foreach ($entry['phonetics'] as $ph) {
                    $phoneticsPool[] = $ph;
                }
            }
        }
        // If no allEntries supplied, fall back to first entry
        if (empty($phoneticsPool) && isset($data['phonetics'])) {
            $phoneticsPool = $data['phonetics'];
        }

        // Build UK / US pronunciation objects
        $ukPron  = null;
        $usPron  = null;
        $genPron = null;

        foreach ($phoneticsPool as $phonetic) {
            $audioUrl = $phonetic['audio'] ?? '';
            $ipaText  = $phonetic['text']  ?? '';

            if (empty($audioUrl) && empty($ipaText)) {
                continue;
            }

            // Fix protocol-relative URLs returned by dictionaryapi.dev  (e.g. //api.dictionaryapi.dev/...)
            if (!empty($audioUrl) && str_starts_with($audioUrl, '//')) {
                $audioUrl = 'https:' . $audioUrl;
            }

            $lowerAudio = strtolower($audioUrl);

            if (strpos($lowerAudio, '-uk') !== false || strpos($lowerAudio, '-gb') !== false) {
                if (!$ukPron || (empty($ukPron['audio']) && !empty($audioUrl))) {
                    $ukPron = ['label' => 'UK', 'ipa' => $ipaText, 'audio' => $audioUrl];
                }
            } elseif (strpos($lowerAudio, '-us') !== false || strpos($lowerAudio, '-au') !== false) {
                if (!$usPron || (empty($usPron['audio']) && !empty($audioUrl))) {
                    $usPron = ['label' => 'US', 'ipa' => $ipaText, 'audio' => $audioUrl];
                }
            } else {
                // Generic (no region in URL) – keep as fallback
                if (!$genPron && !empty($ipaText)) {
                    $genPron = ['label' => '', 'ipa' => $ipaText, 'audio' => $audioUrl];
                }
            }
        }

        // Add UK first, then US; if neither found use generic
        if ($ukPron)  { $transformed['pronunciations'][] = $ukPron;  }
        if ($usPron)  { $transformed['pronunciations'][] = $usPron;  }
        if (!$ukPron && !$usPron && $genPron) {
            $transformed['pronunciations'][] = $genPron;
        }

        // Merge meanings from ALL entries
        $entriesToMerge = !empty($allEntries) ? $allEntries : [$data];
        foreach ($entriesToMerge as $entry) {
            if (!isset($entry['meanings']) || !is_array($entry['meanings'])) {
                continue;
            }
            foreach ($entry['meanings'] as $meaning) {
                $partOfSpeech = $meaning['partOfSpeech'] ?? '';

                $definitions = [];
                if (isset($meaning['definitions']) && is_array($meaning['definitions'])) {
                    foreach ($meaning['definitions'] as $def) {
                        $defSynonyms = array_slice($def['synonyms'] ?? [], 0, 5);
                        $defAntonyms = array_slice($def['antonyms'] ?? [], 0, 5);
                        $definitions[] = [
                            'definition' => $def['definition'] ?? '',
                            'example'    => $def['example']    ?? '',
                            'synonyms'   => $defSynonyms,
                            'antonyms'   => $defAntonyms,
                        ];
                    }
                }

                $meaningSynonyms = array_slice($meaning['synonyms'] ?? [], 0, 8);
                $meaningAntonyms = array_slice($meaning['antonyms'] ?? [], 0, 8);

                // Merge into top-level synonym/antonym pools
                $transformed['synonyms'] = array_unique(array_merge($transformed['synonyms'], $meaningSynonyms));
                $transformed['antonyms'] = array_unique(array_merge($transformed['antonyms'], $meaningAntonyms));

                $transformed['meanings'][] = [
                    'partOfSpeech' => $partOfSpeech,
                    'definitions'  => $definitions,
                    'synonyms'     => $meaningSynonyms,
                    'antonyms'     => $meaningAntonyms,
                ];
            }
        }

        $transformed['synonyms'] = array_values(array_slice($transformed['synonyms'], 0, 10));
        $transformed['antonyms'] = array_values(array_slice($transformed['antonyms'], 0, 10));

        // Generate HTML fallback
        $transformed['htmlContent'] = $this->generateHtmlContent($transformed);

        return $transformed;
    }

    /**
     * Generate HTML content from transformed data
     */
    private function generateHtmlContent($data)
    {
        $html = '';

        // Meanings
        if (!empty($data['meanings'])) {
            foreach ($data['meanings'] as $meaning) {
                $html .= '<div class="definition-section">';
                $html .= '<h3 class="definition-title">' . htmlspecialchars($meaning['partOfSpeech']) . '</h3>';
                
                if (!empty($meaning['definitions'])) {
                    $index = 1;
                    foreach ($meaning['definitions'] as $def) {
                        $html .= '<div class="definition-item">';
                        $html .= '<div class="definition-text"><strong>' . $index . '.</strong> ' . htmlspecialchars($def['definition']) . '</div>';
                        
                        if (!empty($def['example'])) {
                            $html .= '<div class="example-text">"' . htmlspecialchars($def['example']) . '"</div>';
                        }
                        
                        if (!empty($def['synonyms'])) {
                            $html .= '<div class="mt-2 text-sm text-gray-600"><strong>Synonyms:</strong> ' . implode(', ', array_map('htmlspecialchars', $def['synonyms'])) . '</div>';
                        }
                        
                        $html .= '</div>';
                        $index++;
                    }
                }
                
                $html .= '</div>';
            }
        }
        
        return $html;
    }

    /**
     * Get spell checking suggestions (Did you mean?)
     */
    public function didYouMean(Request $request)
    {
        $query = $request->get('q', '');
        $maxResults = $request->get('max', 5);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query is required'
            ], 400);
        }

        try {
            // Generate simple suggestions based on common English words
            // You could enhance this with a proper spell checker library
            $suggestions = $this->generateSuggestions($query, $maxResults);
            
            return response()->json([
                'success' => true,
                'data' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate spelling suggestions for a word
     */
    private function generateSuggestions($word, $max = 5)
    {
        // Common misspellings and corrections
        $commonCorrections = [
            'helo' => 'hello',
            'wrold' => 'world',
            'teh' => 'the',
            'recieve' => 'receive',
            'beleive' => 'believe',
            'occured' => 'occurred',
            'seperate' => 'separate',
            'definately' => 'definitely',
            'wich' => 'which',
            'thier' => 'their',
        ];

        $suggestions = [];
        
        // Check if there's a direct correction
        $lowerWord = strtolower($word);
        if (isset($commonCorrections[$lowerWord])) {
            $suggestions[] = $commonCorrections[$lowerWord];
        }

        // Add some basic variations if we don't have enough suggestions
        if (count($suggestions) < $max) {
            // Try the word with different cases
            if (strlen($word) > 1) {
                $suggestions[] = strtolower($word);
                $suggestions[] = ucfirst(strtolower($word));
            }
        }

        // Remove duplicates and limit results
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, $max);
        
        return array_values($suggestions);
    }

    /**
     * Get nearby entries
     */
    public function getNearbyEntries(Request $request)
    {
        $dictionaryCode = $request->get('dictionary', 'english-vietnamese');
        $entryId = $request->get('entryId', '');
        $max = $request->get('max', 5);

        if (empty($entryId)) {
            return response()->json([
                'success' => false,
                'message' => 'Entry ID is required'
            ], 400);
        }

        // Nearby entries not supported by Free Dictionary API
        return response()->json(['success' => true, 'data' => []]);
    }

    /**
     * Get entry by ID
     */
    public function getEntry(Request $request)
    {
        $dictionaryCode = $request->get('dictionary', 'english-vietnamese');
        $entryId = $request->get('entryId', '');
        $format = $request->get('format', 'html');

        if (empty($entryId)) {
            return response()->json([
                'success' => false,
                'message' => 'Entry ID is required'
            ], 400);
        }

        // Delegate to searchFirst which queries Free Dictionary API
        return $this->searchFirst(new Request(['query' => $entryId]));
    }

    /**
     * Get flashcards preview for dashboard
     */
    public function flashcardsPreview()
    {
        $user = Auth::user();

        $preferredWordList = $this->resolvePreferredWordListForStudent($user);

        if (($preferredWordList['source'] ?? 'my') === 'bundle') {
            $setIds = $preferredWordList['bundleSetIds'] ?? [];

            $bundleWords = BundleVocabularyWord::query()
                ->whereIn('vocabulary_set_id', $setIds)
                ->orderBy('vocabulary_set_id')
                ->orderBy('sort_order')
                ->get();

            if ($bundleWords->isNotEmpty()) {
                $flashcards = $bundleWords->map(function ($word) use ($user) {
                    $flashcard = Flashcard::query()->updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'word' => $word->word,
                            'part_of_speech' => $word->part_of_speech,
                        ],
                        [
                            'pronunciation' => $word->pronunciation,
                            'definition' => $word->definition ?: ($word->translation_vi ?: $word->word),
                            'example' => $word->example,
                            'translation' => $word->translation_vi,
                        ]
                    );

                    return [
                        'id' => $flashcard->id,
                        'word' => $flashcard->word,
                        'part_of_speech' => $flashcard->part_of_speech,
                        'pronunciation' => $flashcard->pronunciation,
                        'definition' => $flashcard->definition,
                        'translation' => $flashcard->translation,
                        'audio_url' => $word->audio_url,
                        'collocation' => $word->collocation,
                        'example' => $flashcard->example,
                        'image_url' => $word->image_url,
                        'source' => 'bundle',
                    ];
                })->values();

                return response()->json([
                    'success' => true,
                    'source' => 'bundle',
                    'data' => $flashcards,
                ]);
            }
        }

        $myWordList = $this->getOrCreatePersonalWordList($user);
        $flashcards = $myWordList
            ->flashcards()
            ->orderBy('flashcard_word_list.order')
            ->get()
            ->map(function ($flashcard) {
                return [
                    'id' => $flashcard->id,
                    'word' => $flashcard->word,
                    'part_of_speech' => $flashcard->part_of_speech,
                    'pronunciation' => $flashcard->pronunciation,
                    'definition' => $flashcard->definition,
                    'translation' => $flashcard->translation,
                    'example' => $flashcard->example,
                    'image_url' => null,
                    'source' => 'my',
                ];
            })->values();

        return response()->json([
            'success' => true,
            'source' => 'my',
            'data' => $flashcards,
        ]);
    }

    /**
     * Flashcard Management
     */
    public function flashcards()
    {
        $user = Auth::user();
        $flashcards = Flashcard::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'pageTitle' => trans('panel.my_flashcards'),
            'flashcards' => $flashcards
        ];

        return view('design_1.panel.dictionary.flashcards', $data);
    }

    /**
     * Save word to flashcard
     */
    public function saveFlashcard(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'word' => 'required|string|max:255',
            'definition' => 'required|string',
            'part_of_speech' => 'nullable|string|max:50',
        ]);

        // For same word with different part of speech, create separate flashcard
        $flashcard = Flashcard::updateOrCreate(
            [
                'user_id' => $user->id,
                'word' => $request->word,
                'part_of_speech' => $request->part_of_speech
            ],
            [
                'definition' => $request->definition,
                'pronunciation' => $request->pronunciation,
                'example' => $request->example,
                'translation' => $request->translation,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => trans('panel.flashcard_saved_successfully'),
            'data' => $flashcard
        ]);
    }

    /**
     * Delete flashcard
     */
    public function deleteFlashcard($id)
    {
        $user = Auth::user();

        $flashcard = Flashcard::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if (!$flashcard) {
            return response()->json([
                'success' => false,
                'message' => trans('panel.flashcard_not_found')
            ], 404);
        }

        $flashcard->delete();

        return response()->json([
            'success' => true,
            'message' => trans('panel.flashcard_deleted_successfully')
        ]);
    }

    /**
     * Bulk delete flashcards from My Word List
     */
    public function bulkDeleteFromMyWordList(Request $request)
    {
        $request->validate([
            'flashcard_ids'   => 'required|array|min:1',
            'flashcard_ids.*' => 'exists:user_flashcards,id',
        ]);

        $user = Auth::user();

        $deleted = Flashcard::where('user_id', $user->id)
            ->whereIn('id', $request->flashcard_ids)
            ->delete();

        // Recalculate word_count on My Word List
        $myWordList = WordList::where('user_id', $user->id)
            ->where('category', 'user')
            ->where('name', 'My Word List')
            ->first();

        if ($myWordList) {
            $myWordList->updateWordCount();
        }

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'message' => $deleted . ' word(s) deleted successfully',
        ]);
    }

    /**
     * Word Lists Management
     */
    public function wordLists()
    {
        $user = Auth::user();

        // Get user's custom word lists
        $userWordLists = WordList::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get predefined IELTS word lists (public lists)
        $ieltsWordLists = WordList::whereNull('user_id')
            ->where('is_public', true)
            ->where('category', 'ielts')
            ->orderBy('level')
            ->get();

        $data = [
            'pageTitle' => trans('panel.word_lists'),
            'userWordLists' => $userWordLists,
            'ieltsWordLists' => $ieltsWordLists,
        ];

        return view('design_1.panel.dictionary.word-lists', $data);
    }

    /**
     * Create new word list
     */
    public function createWordList(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'level' => 'nullable|string|max:50',
        ]);

        $wordList = WordList::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category ?? 'custom',
            'level' => $request->level,
            'is_public' => false,
            'word_count' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_list_created_successfully'),
            'data' => $wordList
        ]);
    }

    /**
     * View word list details
     */
    public function viewWordList($id)
    {
        $user = Auth::user();

        $wordList = WordList::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('is_public', true);
        })
        ->with(['flashcards' => function($query) {
            $query->orderBy('flashcard_word_list.order');
        }])
        ->findOrFail($id);

        $data = [
            'pageTitle' => $wordList->name,
            'wordList' => $wordList,
        ];

        return view('design_1.panel.dictionary.word-list-detail', $data);
    }

    /**
     * Add a word directly to the user's "My Word List" in one step.
     * Creates a flashcard entry behind the scenes – user never sees it.
     */
    public function addWordToMyList(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'word'        => 'required|string|max:255',
            'definition'  => 'required|string',
            'part_of_speech' => 'nullable|string|max:50',
            'pronunciation'  => 'nullable|string|max:255',
            'example'        => 'nullable|string',
        ]);

        // Get or create the user's personal word list
        $wordList = WordList::firstOrCreate(
            [
                'user_id'  => $user->id,
                'category' => 'user',
                'name'     => 'My Word List',
            ],
            [
                'description' => 'My personal vocabulary collection',
                'is_public'   => false,
                'word_count'  => 0,
            ]
        );

        // Upsert the underlying flashcard record
        $flashcard = Flashcard::updateOrCreate(
            [
                'user_id'        => $user->id,
                'word'           => $request->word,
                'part_of_speech' => $request->part_of_speech,
            ],
            [
                'definition'    => $request->definition,
                'pronunciation' => $request->pronunciation,
                'example'       => $request->example,
            ]
        );

        // Already in list?
        if ($wordList->flashcards()->where('user_flashcards.id', $flashcard->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => trans('panel.word_already_in_list'),
            ], 400);
        }

        $maxOrder = $wordList->flashcards()->max('flashcard_word_list.order') ?? 0;
        $wordList->flashcards()->attach($flashcard->id, ['order' => $maxOrder + 1]);
        $wordList->updateWordCount();

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_added_to_list_successfully'),
            'data'    => [
                'word_count' => $wordList->word_count,
            ],
        ]);
    }

    /**
     * Add word to list
     */
    public function addWordToList(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'word_list_id' => 'required|exists:word_lists,id',
            'flashcard_id' => 'required|exists:flashcards,id',
        ]);

        $wordList = WordList::where('user_id', $user->id)
            ->findOrFail($request->word_list_id);

        // Check if word already exists in list
        if ($wordList->flashcards()->where('flashcard_id', $request->flashcard_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => trans('panel.word_already_in_list')
            ], 400);
        }

        // Get next order number
        $maxOrder = $wordList->flashcards()->max('flashcard_word_list.order') ?? 0;

        // Add flashcard to word list
        $wordList->flashcards()->attach($request->flashcard_id, [
            'order' => $maxOrder + 1
        ]);

        // Update word count
        $wordList->updateWordCount();

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_added_to_list_successfully')
        ]);
    }

    /**
     * Remove word from list
     */
    public function removeWordFromList(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'word_list_id' => 'required|exists:word_lists,id',
            'flashcard_id' => 'required|exists:flashcards,id',
        ]);

        $wordList = WordList::where('user_id', $user->id)
            ->findOrFail($request->word_list_id);

        $wordList->flashcards()->detach($request->flashcard_id);

        // Update word count
        $wordList->updateWordCount();

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_removed_from_list_successfully')
        ]);
    }

    /**
     * Update word list
     */
    public function updateWordList(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $wordList = WordList::where('user_id', $user->id)
            ->findOrFail($id);

        $wordList->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_list_updated_successfully'),
            'data' => $wordList
        ]);
    }

    /**
     * Delete word list
     */
    public function deleteWordList($id)
    {
        $user = Auth::user();

        $wordList = WordList::where('user_id', $user->id)
            ->findOrFail($id);

        $wordList->flashcards()->detach();
        $wordList->delete();

        return response()->json([
            'success' => true,
            'message' => trans('panel.word_list_deleted_successfully')
        ]);
    }

    /**
     * Get user's word lists for dropdown
     */
    public function getUserWordLists()
    {
        $user = Auth::user();

        $wordLists = WordList::where('user_id', $user->id)
            ->select('id', 'name', 'word_count')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wordLists
        ]);
    }

    /**
     * Get word list details with flashcards and progress
     */
    public function getWordListDetails($id)
    {
        $user = Auth::user();
        $wordList = WordList::with(['flashcards'])->findOrFail($id);

        // Check if user has access to this word list
        if ($wordList->type === 'user' && $wordList->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Get flashcards with user progress
        $flashcards = $wordList->flashcards->map(function($flashcard) use ($user, $id) {
            $progress = UserWordProgress::where('user_id', $user->id)
                ->where('flashcard_id', $flashcard->id)
                ->where('word_list_id', $id)
                ->first();

            return [
                'id' => $flashcard->id,
                'word' => $flashcard->word,
                'pronunciation' => $flashcard->pronunciation,
                'definition' => $flashcard->definition,
                'example' => $flashcard->example,
                'translation' => $flashcard->translation,
                'is_learned' => $progress ? $progress->is_learned : false,
                'correct_count' => $progress ? $progress->correct_count : 0,
                'incorrect_count' => $progress ? $progress->incorrect_count : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'word_list' => $wordList,
                'flashcards' => $flashcards,
            ]
        ]);
    }





    /**
     * Mark word as learned
     */
    public function markAsLearned(Request $request)
    {
        $request->validate([
            'word_list_id' => 'required|exists:word_lists,id',
            'flashcard_id' => 'required|exists:flashcards,id',
        ]);

        $user = Auth::user();

        UserWordProgress::markAsLearned(
            $user->id,
            $request->flashcard_id,
            $request->word_list_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Word marked as learned'
        ]);
    }

    /**
     * Add word to My Word List from dictionary search
     */
    public function addToMyWordList(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
            'pronunciation' => 'nullable|string',
            'definition' => 'required|string',
            'example' => 'nullable|string',
            'translation' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Get or create My Word List
        $myWordList = WordList::firstOrCreate(
            [
                'user_id' => $user->id,
                'category' => 'user',
                'name' => 'My Word List'
            ],
            [
                'description' => 'My personal vocabulary collection',
                'is_public' => false,
                'word_count' => 0
            ]
        );

        // Check if word already exists in flashcards
        $flashcard = Flashcard::firstOrCreate(
            [
                'user_id' => $user->id,
                'word' => $request->word,
            ],
            [
                'pronunciation' => $request->pronunciation,
                'definition' => $request->definition,
                'example' => $request->example,
                'translation' => $request->translation,
            ]
        );

        // Add to word list if not already there
        if (!$myWordList->flashcards()->where('flashcard_id', $flashcard->id)->exists()) {
            $maxOrder = $myWordList->flashcards()->max('order') ?? 0;
            $myWordList->flashcards()->attach($flashcard->id, ['order' => $maxOrder + 1]);
            $myWordList->updateWordCount();
        }

        return response()->json([
            'success' => true,
            'message' => 'Word added to My Word List',
            'flashcard_id' => $flashcard->id,
        ]);
    }

    /**
     * Remove word from word list (My Word List only)
     */
    public function removeFromWordList(Request $request)
    {
        $request->validate([
            'word_list_id' => 'required|exists:word_lists,id',
            'flashcard_id' => 'required|exists:flashcards,id',
        ]);

        $user = Auth::user();
        $wordList = WordList::findOrFail($request->word_list_id);

        // Only allow removing from own word lists
        if ($wordList->user_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $wordList->flashcards()->detach($request->flashcard_id);
        $wordList->updateWordCount();

        return response()->json([
            'success' => true,
            'message' => 'Word removed from word list'
        ]);
    }

    /**
     * Get academic word list details with words
     */
    public function getAcademicWordList($id)
    {
        $user = Auth::user();
        $wordList = AcademicWordList::with('words')->findOrFail($id);

        // Check access
        if (!$wordList->hasAccess($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this word list'
            ], 403);
        }

        // Get user progress for each word
        $words = $wordList->words->map(function($word) use ($user) {
            $progress = UserWordProgress::where('user_id', $user->id)
                ->where('academic_word_list_word_id', $word->id)
                ->first();

            return [
                'id' => $word->id,
                'word' => $word->word,
                'pronunciation' => $word->pronunciation,
                'definition' => $word->definition,
                'example' => $word->example,
                'translation' => $word->translation,
                'is_learned' => $progress ? $progress->is_learned : false,
                'practice_count' => $progress ? $progress->practice_count : 0,
                'correct_count' => $progress ? $progress->correct_count : 0,
                'word_source' => 'academic',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $wordList->id,
                'name' => $wordList->name,
                'description' => $wordList->description,
                'band_level' => $wordList->band_level,
                'word_count' => $wordList->word_count,
                'words' => $words,
            ]
        ]);
    }

    /**
     * Get bundle vocabulary set details (rendered under Academic Word List section).
     */
    public function getBundleWordList($id)
    {
        $user = Auth::user();

        $set = $this->getApprovedBundleVocabularySetForUser($user, (int) $id);

        if (!$set) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this bundle vocabulary set',
            ], 403);
        }

        $mappedWords = $this->buildBundleVocabularyFlashcardsForSet($user, $set->id);

        $bundleTitle = $set->bundle ? ($set->bundle->title ?: $set->bundle->slug) : ('Bundle #' . $set->bundle_id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $set->id,
                'name' => $set->name,
                'description' => $bundleTitle . ' - ' . ($set->description ?: ''),
                'band_level' => 'Bundle',
                'word_count' => $mappedWords->count(),
                'words' => $mappedWords,
                'source_type' => 'bundle',
            ]
        ]);
    }

    /**
     * Mark word as learned
     */
    public function markWordAsLearned(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:academic_word_list_words,id',
        ]);

        $user = Auth::user();
        $word = AcademicWordListWord::findOrFail($request->word_id);

        // Check access to the word list
        if (!$word->academicWordList->hasAccess($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $progress = UserWordProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'academic_word_list_word_id' => $word->id,
            ],
            [
                'word' => $word->word,
                'is_learned' => true,
                'learned_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Word marked as learned'
        ]);
    }

    /**
     * Start practice session for academic word list
     */
    public function startPractice(Request $request)
    {
        $request->validate([
            'word_list_id' => 'required|exists:academic_word_lists,id',
            'word_ids' => 'nullable|array',
            'word_ids.*' => 'exists:academic_word_list_words,id',
        ]);

        $user = Auth::user();
        $wordList = AcademicWordList::with('words')->findOrFail($request->word_list_id);

        // Check access
        if (!$wordList->hasAccess($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Get words to practice (selected words or all words)
        if ($request->word_ids && count($request->word_ids) > 0) {
            $practiceWords = $wordList->words->whereIn('id', $request->word_ids);
        } else {
            $practiceWords = $wordList->words;
        }

        if ($practiceWords->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No words to practice'
            ], 400);
        }

        // Generate practice questions
        $questions = [];
        foreach ($practiceWords as $word) {
            // Get 3 random wrong answers from the same word list
            $wrongAnswers = $wordList->words
                ->where('id', '!=', $word->id)
                ->random(min(3, $wordList->words->count() - 1))
                ->pluck('word')
                ->toArray();

            // Combine with correct answer and shuffle
            $answers = array_merge([$word->word], $wrongAnswers);
            shuffle($answers);

            $questions[] = [
                'word_id' => $word->id,
                'question' => $word->definition,
                'correct_answer' => $word->word,
                'answers' => $answers,
            ];
        }

        // Shuffle questions
        shuffle($questions);

        return response()->json([
            'success' => true,
            'data' => [
                'word_list_id' => $wordList->id,
                'word_list_name' => $wordList->name,
                'total_questions' => count($questions),
                'questions' => $questions,
            ]
        ]);
    }

    /**
     * Start practice session for My Word List
     */
    public function startMyWordListPractice(Request $request)
    {
        $request->validate([
            'flashcard_ids' => 'nullable|array',
            'flashcard_ids.*' => 'exists:user_flashcards,id',
        ]);

        $user = Auth::user();

        $myWordList = $this->getOrCreatePersonalWordList($user);

        if (!$myWordList || $myWordList->flashcards()->count() < 1) {
            return response()->json([
                'success' => false,
                'message' => 'My Word List not found'
            ], 404);
        }

        $myWordList->load('flashcards');

        // Get flashcards to practice
        if ($request->flashcard_ids && count($request->flashcard_ids) > 0) {
            $practiceFlashcards = $myWordList->flashcards->whereIn('id', $request->flashcard_ids);
        } else {
            $practiceFlashcards = $myWordList->flashcards;
        }

        if ($practiceFlashcards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No words to practice'
            ], 400);
        }

        // Generate practice questions
        $questions = [];
        $allFlashcards = $myWordList->flashcards;
        foreach ($practiceFlashcards as $flashcard) {
            // Get up to 3 random wrong answers from My Word List (with pronunciation)
            $wrongFlashcards = $allFlashcards
                ->where('id', '!=', $flashcard->id)
                ->random(min(3, max(0, $allFlashcards->count() - 1)));

            // Build answer objects (word + pronunciation)
            $answersData = [
                ['word' => $flashcard->word, 'pronunciation' => $flashcard->pronunciation ?? ''],
            ];
            foreach ($wrongFlashcards as $wf) {
                $answersData[] = ['word' => $wf->word, 'pronunciation' => $wf->pronunciation ?? ''];
            }
            shuffle($answersData);

            $questions[] = [
                'flashcard_id' => $flashcard->id,
                'question' => $flashcard->definition,
                'correct_answer' => $flashcard->word,
                'answers' => $answersData,
            ];
        }

        // Shuffle questions
        shuffle($questions);

        return response()->json([
            'success' => true,
            'data' => [
                'word_list_id' => $myWordList->id,
                'word_list_name' => $myWordList->name,
                'source' => 'my',
                'total_questions' => count($questions),
                'questions' => $questions,
            ]
        ]);
    }

    /**
     * Start practice session for bundle vocabulary list shown under Academic section.
     */
    public function startBundleWordListPractice(Request $request)
    {
        $request->validate([
            'vocabulary_set_id' => 'required|integer',
            'flashcard_ids' => 'nullable|array',
            'flashcard_ids.*' => 'exists:user_flashcards,id',
        ]);

        $user = Auth::user();
        $set = $this->getApprovedBundleVocabularySetForUser($user, (int) $request->vocabulary_set_id);

        if (!$set) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this bundle vocabulary set',
            ], 403);
        }

        $allFlashcards = $this->buildBundleVocabularyFlashcardsForSet($user, $set->id);

        if ($allFlashcards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No words found in this bundle vocabulary set',
            ], 404);
        }

        if ($request->flashcard_ids && count($request->flashcard_ids) > 0) {
            $practiceFlashcards = $allFlashcards->whereIn('id', $request->flashcard_ids);
        } else {
            $practiceFlashcards = $allFlashcards;
        }

        if ($practiceFlashcards->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No words to practice'
            ], 400);
        }

        $questions = [];
        foreach ($practiceFlashcards as $flashcard) {
            $wrongFlashcards = $allFlashcards
                ->where('id', '!=', $flashcard['id'])
                ->shuffle()
                ->take(min(3, max(0, $allFlashcards->count() - 1)));

            $answersData = [
                ['word' => $flashcard['word'], 'pronunciation' => $flashcard['pronunciation'] ?? ''],
            ];
            foreach ($wrongFlashcards as $wf) {
                $answersData[] = ['word' => $wf['word'], 'pronunciation' => $wf['pronunciation'] ?? ''];
            }
            shuffle($answersData);

            $questions[] = [
                'flashcard_id' => $flashcard['id'],
                'question' => $flashcard['definition'],
                'correct_answer' => $flashcard['word'],
                'answers' => $answersData,
            ];
        }

        shuffle($questions);

        return response()->json([
            'success' => true,
            'data' => [
                'word_list_id' => $set->id,
                'word_list_name' => $set->name,
                'source' => 'bundle',
                'total_questions' => count($questions),
                'questions' => $questions,
            ]
        ]);
    }

    /**
     * Submit practice answer
     */
    public function submitPracticeAnswer(Request $request)
    {
        $request->validate([
            'word_id' => 'nullable|exists:academic_word_list_words,id',
            'flashcard_id' => 'nullable|exists:user_flashcards,id',
            'selected_answer' => 'required|string',
            'correct_answer' => 'required|string',
        ]);

        $user = Auth::user();
        $isCorrect = $request->selected_answer === $request->correct_answer;

        // Update progress based on word source
        if ($request->word_id) {
            // Academic word list word
            $progress = UserWordProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'academic_word_list_word_id' => $request->word_id,
                ],
                [
                    'word' => $request->correct_answer,
                    'last_practiced_at' => now(),
                ]
            );

            $progress->increment('practice_count');
            if ($isCorrect) {
                $progress->increment('correct_count');

                // Persist learned state immediately after first correct answer.
                if (!$progress->is_learned) {
                    $progress->update([
                        'is_learned' => true,
                        'learned_at' => now(),
                    ]);
                }
            }
        } elseif ($request->flashcard_id) {
            // My Word List flashcard
            $progress = UserWordProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'flashcard_id' => $request->flashcard_id,
                ],
                [
                    'word' => $request->correct_answer,
                    'last_practiced_at' => now(),
                ]
            );

            $progress->increment('practice_count');
            if ($isCorrect) {
                $progress->increment('correct_count');

                // Persist learned state immediately after first correct answer.
                if (!$progress->is_learned) {
                    $progress->update([
                        'is_learned' => true,
                        'learned_at' => now(),
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'message' => $isCorrect ? 'Correct!' : 'Incorrect',
        ]);
    }

    /**
     * Get My Word List details
     */
    public function getMyWordList()
    {
        $user = Auth::user();

        $myWordList = $this->getOrCreatePersonalWordList($user);

        if (!$myWordList) {
            return response()->json([
                'success' => false,
                'message' => 'My Word List not found'
            ], 404);
        }

        $myWordList->load('flashcards');

        // Get user progress for each flashcard
        $flashcards = $myWordList->flashcards->map(function($flashcard) use ($user) {
            $progress = UserWordProgress::where('user_id', $user->id)
                ->where('flashcard_id', $flashcard->id)
                ->first();

            return [
                'id' => $flashcard->id,
                'word' => $flashcard->word,
                'part_of_speech' => $flashcard->part_of_speech,
                'pronunciation' => $flashcard->pronunciation,
                'definition' => $flashcard->definition,
                'example' => $flashcard->example,
                'translation' => $flashcard->translation,
                'audio_url' => null,
                'collocation' => null,
                'image_url' => null,
                'is_learned' => $progress ? $progress->is_learned : false,
                'practice_count' => $progress ? $progress->practice_count : 0,
                'correct_count' => $progress ? $progress->correct_count : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $myWordList->id,
                'name' => $myWordList->name,
                'description' => $myWordList->description,
                'word_count' => $myWordList->word_count,
                'source' => 'my',
                'bundle_vocabulary_set_ids' => [],
                'flashcards' => $flashcards,
            ]
        ]);
    }

    private function getOrCreatePersonalWordList($user): WordList
    {
        return WordList::with('flashcards')->firstOrCreate(
            [
                'user_id' => $user->id,
                'category' => 'user',
                'name' => 'My Word List'
            ],
            [
                'description' => 'My personal vocabulary collection',
                'is_public' => false,
                'word_count' => 0
            ]
        );
    }

    private function getApprovedBundleVocabularySetForUser($user, int $setId): ?BundleVocabularySet
    {
        $allowedSetIds = $this->getApprovedBundleVocabularySetsForUser($user)->pluck('id')->toArray();

        if (!in_array($setId, $allowedSetIds, true)) {
            return null;
        }

        return BundleVocabularySet::query()
            ->with(['bundle:id,slug'])
            ->approved()
            ->find($setId);
    }

    private function buildBundleVocabularyFlashcardsForSet($user, int $setId)
    {
        $words = BundleVocabularyWord::query()
            ->where('vocabulary_set_id', $setId)
            ->orderBy('sort_order')
            ->get();

        return $words->map(function ($word) use ($user) {
            $flashcard = Flashcard::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'word' => $word->word,
                    'part_of_speech' => $word->part_of_speech,
                ],
                [
                    'pronunciation' => $word->pronunciation,
                    'definition' => $word->definition ?: ($word->translation_vi ?: $word->word),
                    'example' => $word->example,
                    'translation' => $word->translation_vi,
                ]
            );

            $progress = UserWordProgress::where('user_id', $user->id)
                ->where('flashcard_id', $flashcard->id)
                ->first();

            return [
                'id' => $flashcard->id,
                'word' => $flashcard->word,
                'part_of_speech' => $flashcard->part_of_speech,
                'pronunciation' => $flashcard->pronunciation,
                'definition' => $flashcard->definition,
                'example' => $flashcard->example,
                'translation' => $flashcard->translation,
                'audio_url' => $word->audio_url,
                'collocation' => $word->collocation,
                'is_learned' => $progress ? $progress->is_learned : false,
                'practice_count' => $progress ? $progress->practice_count : 0,
                'correct_count' => $progress ? $progress->correct_count : 0,
                'word_source' => 'bundle',
                'image_url' => $word->image_url,
            ];
        });
    }

    /**
     * Bundle Vocabulary Management page for teacher/admin/manager/ceo.
     */
    public function bundleVocabularyManage(Request $request)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canManageBundleVocabulary()) {
            abort(403);
        }

        $status = $request->get('status');

        $setsQuery = BundleVocabularySet::query()
            ->with(['bundle:id,slug,creator_id,teacher_id', 'creator:id,full_name', 'approver:id,full_name'])
            ->withCount('words');

        if ($user->canApproveBundleVocabulary()) {
            if (in_array($status, BundleVocabularySet::$statuses)) {
                $setsQuery->where('status', $status);
            }
        } else {
            $setsQuery->where('created_by', $user->id);
        }

        $sets = $setsQuery->orderByDesc('id')->paginate(20);

        $data = [
            'pageTitle' => trans('panel.bundle_vocabulary_library'),
            'sets' => $sets,
            'status' => $status,
            'availableBundles' => $this->getAvailableBundlesForVocabulary($user),
            'canApprove' => $user->canApproveBundleVocabulary(),
        ];

        return view('design_1.panel.dictionary.bundle_vocabulary_manage', $data);
    }

    public function storeBundleVocabularySet(Request $request)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canManageBundleVocabulary()) {
            abort(403);
        }

        $request->validate([
            'bundle_id' => 'required|integer|exists:bundles,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'intro_content' => 'nullable|string',
            'feature_content' => 'nullable|string',
            'source_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:20480',
        ]);

        $bundle = Bundle::query()->findOrFail((int)$request->bundle_id);
        $this->assertUserCanUploadToBundle($user, $bundle->id);

        $parsedWords = $this->parseUploadedVocabularyFile($request->file('source_file'));

        if (empty($parsedWords)) {
            return back()->withErrors([
                'source_file' => trans('panel.bundle_vocabulary_no_valid_words'),
            ])->withInput();
        }

        DB::beginTransaction();

        try {
            $set = BundleVocabularySet::query()->create([
                'bundle_id' => $bundle->id,
                'created_by' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
                'intro_content' => $request->intro_content,
                'feature_content' => $request->feature_content,
                'status' => BundleVocabularySet::STATUS_DRAFT,
                'words_count' => 0,
            ]);

            if ($request->hasFile('source_file')) {
                $diskPath = $request->file('source_file')->store('bundle-vocabulary', 'public');
                $set->source_file_path = $diskPath;
                $set->save();
            }

            $this->replaceVocabularySetWords($set, $parsedWords);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Bundle vocabulary upload failed', [
                'user_id' => $user->id,
                'bundle_id' => $bundle->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'source_file' => trans('panel.bundle_vocabulary_upload_failed'),
            ])->withInput();
        }

        return redirect('/panel/dictionary/bundle-vocabulary/manage')
            ->with('success', trans('panel.bundle_vocabulary_draft_created'));
    }

    public function showBundleVocabularySet($id)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canManageBundleVocabulary()) {
            abort(403);
        }

        $set = BundleVocabularySet::query()
            ->with(['bundle:id,slug,creator_id,teacher_id', 'creator:id,full_name', 'approver:id,full_name'])
            ->findOrFail($id);

        if (!$user->canApproveBundleVocabulary() && $set->created_by !== $user->id) {
            abort(403);
        }

        $words = BundleVocabularyWord::query()
            ->where('vocabulary_set_id', $set->id)
            ->orderBy('sort_order')
            ->paginate(100);

        $data = [
            'pageTitle' => trans('panel.bundle_vocabulary_set_detail'),
            'set' => $set,
            'words' => $words,
            'canApprove' => $user->canApproveBundleVocabulary(),
            'availableBundles' => $this->getAvailableBundlesForVocabulary($user),
        ];

        return view('design_1.panel.dictionary.bundle_vocabulary_show', $data);
    }

    public function updateBundleVocabularySet(Request $request, $id)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canManageBundleVocabulary()) {
            abort(403);
        }

        $set = BundleVocabularySet::query()->findOrFail($id);

        if (!$user->canApproveBundleVocabulary() && $set->created_by !== $user->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'intro_content' => 'nullable|string',
            'feature_content' => 'nullable|string',
            'source_file' => 'nullable|file|mimes:csv,txt,xlsx,xls|max:20480',
        ]);

        DB::beginTransaction();

        try {
            $set->name = $request->name;
            $set->description = $request->description;
            $set->intro_content = $request->intro_content;
            $set->feature_content = $request->feature_content;

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
                $set->status = BundleVocabularySet::STATUS_DRAFT;
                $set->submitted_at = null;
                $set->approved_at = null;
                $set->approved_by = null;
                $set->rejected_at = null;
                $set->rejection_note = null;
                $set->save();

                $this->replaceVocabularySetWords($set, $parsedWords);
            } else {
                $set->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Bundle vocabulary set update failed', [
                'set_id' => $id,
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'source_file' => trans('panel.bundle_vocabulary_update_failed'),
            ])->withInput();
        }

        return back()->with('success', trans('panel.bundle_vocabulary_updated'));
    }

    public function submitBundleVocabularySet($id)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canManageBundleVocabulary()) {
            abort(403);
        }

        $set = BundleVocabularySet::query()->findOrFail($id);

        if ($set->created_by !== $user->id && !$user->canApproveBundleVocabulary()) {
            abort(403);
        }

        if ((int)$set->words_count < 1) {
            return back()->withErrors([
                'submit' => trans('panel.bundle_vocabulary_submit_requires_words'),
            ]);
        }

        $set->status = BundleVocabularySet::STATUS_PENDING;
        $set->submitted_at = now();
        $set->approved_at = null;
        $set->rejected_at = null;
        $set->rejection_note = null;
        $set->approved_by = null;
        $set->save();

        return back()->with('success', trans('panel.bundle_vocabulary_submitted'));
    }

    public function approveBundleVocabularySet($id)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canApproveBundleVocabulary()) {
            abort(403);
        }

        $set = BundleVocabularySet::query()->findOrFail($id);

        if ((int)$set->words_count < 1) {
            return back()->withErrors([
                'approve' => trans('panel.bundle_vocabulary_submit_requires_words'),
            ]);
        }

        $set->status = BundleVocabularySet::STATUS_APPROVED;
        $set->approved_by = $user->id;
        $set->approved_at = now();
        $set->rejected_at = null;
        $set->rejection_note = null;
        $set->save();

        return back()->with('success', trans('panel.bundle_vocabulary_approved'));
    }

    public function rejectBundleVocabularySet(Request $request, $id)
    {
        $user = Auth::user();
        /** @var User $user */

        if (!$user->canApproveBundleVocabulary()) {
            abort(403);
        }

        $request->validate([
            'rejection_note' => 'required|string|min:3|max:2000',
        ]);

        $set = BundleVocabularySet::query()->findOrFail($id);

        $set->status = BundleVocabularySet::STATUS_REJECTED;
        $set->approved_by = $user->id;
        $set->approved_at = null;
        $set->rejected_at = now();
        $set->rejection_note = $request->rejection_note;
        $set->save();

        return back()->with('success', trans('panel.bundle_vocabulary_rejected'));
    }

    private function getAvailableBundlesForVocabulary($user)
    {
        $query = Bundle::query()->select('id', 'slug', 'creator_id', 'teacher_id');

        if (!$user->canApproveBundleVocabulary()) {
            $organizationTeacherIds = [];
            if ($user->isOrganization()) {
                $organizationTeacherIds = $user->getOrganizationTeachers()->pluck('id')->toArray();
            }

            $query->where(function ($subQuery) use ($user, $organizationTeacherIds) {
                $subQuery->where('creator_id', $user->id)
                    ->orWhere('teacher_id', $user->id);

                if (!empty($organizationTeacherIds)) {
                    $subQuery->orWhereIn('teacher_id', $organizationTeacherIds);
                }
            });
        }

        return $query->orderByDesc('id')->get();
    }

    private function assertUserCanUploadToBundle($user, int $bundleId): void
    {
        if ($user->canApproveBundleVocabulary()) {
            return;
        }

        $allowedBundleIds = $this->getAvailableBundlesForVocabulary($user)->pluck('id')->toArray();

        if (!in_array($bundleId, $allowedBundleIds)) {
            abort(403);
        }
    }

    private function parseUploadedVocabularyFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if (in_array($extension, ['csv', 'txt'])) {
            $handle = fopen($file->getRealPath(), 'r');

            if ($handle !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = $row;
                }

                fclose($handle);
            }
        } else {
            $rows = $this->extractSpreadsheetRows($file);
        }

        if (empty($rows)) {
            return [];
        }

        [$columnMap, $startIndex] = $this->resolveVocabularyColumnMap($rows);

        $parsed = [];
        $sortOrder = 1;

        for ($i = $startIndex; $i < count($rows); $i++) {
            $row = $rows[$i];

            if (!is_array($row)) {
                continue;
            }

            $word = $this->cleanCellValue($row[$columnMap['word']] ?? null);

            if (empty($word)) {
                continue;
            }

            $definition = $this->cleanCellValue($row[$columnMap['definition']] ?? null);
            $translation = $this->cleanCellValue($row[$columnMap['translation']] ?? null);

            if (empty($definition)) {
                $definition = $translation;
            }

            if (empty($translation)) {
                $translation = $definition;
            }

            if (empty($translation)) {
                $translation = $this->translateToVietnamese($word);
            }

            $imageUrl = $this->sanitizeExternalUrl($row[$columnMap['image_url']] ?? null);
            $imageUrl = $imageUrl ? $this->convertGoogleDriveUrl($imageUrl, 'view') : null;
            if (empty($imageUrl)) {
                $imageUrl = $this->buildVocabularyIllustrationUrl($word);
            }

            $audioUrl = $this->sanitizeExternalUrl($row[$columnMap['audio_url']] ?? null);
            $audioUrl = $audioUrl ? $this->convertGoogleDriveUrl($audioUrl, 'audio') : null;

            $parsed[] = [
                'word' => $word,
                'part_of_speech' => $this->cleanCellValue($row[$columnMap['part_of_speech']] ?? null),
                'pronunciation' => $this->cleanCellValue($row[$columnMap['pronunciation']] ?? null),
                'definition' => $definition,
                'translation_vi' => $translation,
                'audio_url' => $audioUrl,
                'collocation' => $this->cleanCellValue($row[$columnMap['collocation']] ?? null),
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
        $legacyDefaultMap = [
            'word' => 0,
            'definition' => 1,
            'translation' => 2,
            'example' => 3,
            'part_of_speech' => 4,
            'pronunciation' => 5,
            'image_url' => 6,
            'audio_url' => 7,
            'collocation' => 8,
        ];

        $newDefaultMap = [
            'word' => 0,
            'part_of_speech' => 1,
            'image_url' => 2,
            'translation' => 3,
            'pronunciation' => 4,
            'audio_url' => 5,
            'collocation' => 6,
            'example' => 7,
            'definition' => 3,
        ];

        $header = $rows[0] ?? [];
        if (!is_array($header)) {
            return [$legacyDefaultMap, 0];
        }

        $normalizedHeader = array_map([$this, 'normalizeVocabularyHeader'], $header);

        $aliases = [
            'word' => ['word', 'term', 'vocabulary', 'tu'],
            'part_of_speech' => ['part_of_speech', 'part_of_speech_pos', 'part_of_speech_word_type', 'part_of_speech_type', 'pos', 'word_type', 'type', 'loai_tu'],
            'image_url' => ['image', 'image_url', 'image_link', 'illustration', 'hinh_anh', 'hinh'],
            'translation' => ['translation', 'translation_vi', 'vietnamese', 'vi', 'nghia', 'meaning'],
            'definition' => ['definition', 'explanation', 'dinh_nghia'],
            'pronunciation' => ['pronunciation', 'ipa', 'phonetic', 'phien_am'],
            'audio_url' => ['audio', 'audio_url', 'audio_link', 'audio_hyperlink', 'sound', 'phat_am'],
            'collocation' => ['collocation', 'collocations', 'cum_tu'],
            'example' => ['example', 'sample', 'example_sentence', 'vi_du'],
        ];

        $columnCount = count($header);
        $resolvedMap = $columnCount >= 8 ? $newDefaultMap : $legacyDefaultMap;
        $hasHeader = false;
        $matchedHeaderColumns = 0;

        foreach ($aliases as $key => $possibleNames) {
            foreach ($possibleNames as $name) {
                $index = array_search($name, $normalizedHeader, true);
                if ($index !== false) {
                    $resolvedMap[$key] = $index;
                    $matchedHeaderColumns++;
                    break;
                }
            }
        }

        if ($matchedHeaderColumns >= 2) {
            $hasHeader = true;
        }

        return [$resolvedMap, $hasHeader ? 1 : 0];
    }

    private function normalizeVocabularyHeader($value): string
    {
        $normalized = trim((string) $value);
        $normalized = preg_replace('/^[\x{FEFF}\x{200B}\x{2060}]+/u', '', $normalized);
        $normalized = mb_strtolower($normalized);
        $normalized = Str::ascii($normalized);
        $normalized = $this->replaceVietnameseAccents($normalized);
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized);

        return trim((string) $normalized, '_');
    }

    private function replaceVietnameseAccents(string $value): string
    {
        return strtr($value, [
            'à' => 'a', 'á' => 'a', 'ạ' => 'a', 'ả' => 'a', 'ã' => 'a',
            'â' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a',
            'ă' => 'a', 'ằ' => 'a', 'ắ' => 'a', 'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a',
            'è' => 'e', 'é' => 'e', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e',
            'ê' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e', 'ể' => 'e', 'ễ' => 'e',
            'ì' => 'i', 'í' => 'i', 'ị' => 'i', 'ỉ' => 'i', 'ĩ' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ọ' => 'o', 'ỏ' => 'o', 'õ' => 'o',
            'ô' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o', 'ổ' => 'o', 'ỗ' => 'o',
            'ơ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o',
            'ù' => 'u', 'ú' => 'u', 'ụ' => 'u', 'ủ' => 'u', 'ũ' => 'u',
            'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u',
            'ỳ' => 'y', 'ý' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
            'đ' => 'd',
        ]);
    }

    private function extractSpreadsheetRows(UploadedFile $file): array
    {
        try {
            // $spreadsheet = IOFactory::load($file->getRealPath());
            // $sheet = $spreadsheet->getSheet(0);
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getSheet(0);
            $xmlHyperlinks = $this->extractAllHyperlinksFromXml($file->getRealPath(), 0);
            \Log::info('XML Hyperlinks extracted', ['count' => count($xmlHyperlinks), 'sample' => array_slice($xmlHyperlinks, 0, 5, true)]);

            $highestRow = (int) $sheet->getHighestDataRow();
            $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            $rows = [];

            for ($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++) {
                $rowValues = [];
                $hasValue = false;

                for ($columnIndex = 1; $columnIndex <= $highestColumnIndex; $columnIndex++) {
                    $coordinate = Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex;
                    $cell = $sheet->getCell($coordinate);

                    $value = $cell->getFormattedValue();
                    if (in_array(Coordinate::stringFromColumnIndex($columnIndex), ['C', 'F'])) {
                        \Log::info('Cell debug', [
                            'coordinate' => Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex,
                            'formatted_value' => $cell->getFormattedValue(),
                            'raw_value' => $cell->getValue(),
                            'raw_value_type' => gettype($cell->getValue()),
                            'is_richtext' => ($cell->getValue() instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText),
                        ]);
                    }
                    $hyperlink = $this->extractSpreadsheetCellHyperlink($cell, $xmlHyperlinks);

                    if (!empty($hyperlink)) {
                        $value = $hyperlink;
                    } else {
                        // Try to extract URL from =HYPERLINK() formula directly
                        $rawValue = $cell->getValue();
                        if (is_string($rawValue) && stripos($rawValue, '=HYPERLINK(') === 0) {
                            if (preg_match('/=HYPERLINK\s*\(\s*["\']([^"\']+)["\']/i', $rawValue, $m)) {
                                $value = trim($m[1]);
                            }
                        }
                    }

                    if (!empty($hyperlink)) {
                        $value = $hyperlink;
                    }

                    if (!$hasValue && trim((string) $value) !== '') {
                        $hasValue = true;
                    }

                    $rowValues[] = $value;
                }

                if ($hasValue) {
                    $rows[] = $rowValues;
                }
            }

            return $rows;
        } catch (\Throwable $e) {
            Log::warning('Spreadsheet hyperlink parsing failed, fallback to toArray', [
                'file' => $file->getClientOriginalName(),
                'message' => $e->getMessage(),
            ]);

            $sheets = Excel::toArray([], $file);

            return $sheets[0] ?? [];
        }
    }

    private function extractSpreadsheetCellHyperlink($cell, array $xmlHyperlinks = []): ?string
    {
        // First: try XML hyperlinks map (most reliable for Google Sheets exports)
        $coordinate = $cell->getCoordinate();
        \Log::info('Hyperlink lookup', [
            'coordinate' => $coordinate,
            'found_in_xml' => isset($xmlHyperlinks[$coordinate]),
            'xml_value' => $xmlHyperlinks[$coordinate] ?? 'NOT_FOUND',
        ]);
        if (!empty($xmlHyperlinks[$coordinate])) {
            return trim((string) $xmlHyperlinks[$coordinate]);
        }

        // Fallback: PhpSpreadsheet native cell hyperlink
        try {
            $hyperlink = $cell->getHyperlink();
            if (!empty($hyperlink) && !empty($hyperlink->getUrl())) {
                return trim((string) $hyperlink->getUrl());
            }
        } catch (\Throwable $e) {
            // Ignore and continue with formula parsing.
        }

        $rawValue = $cell->getValue();
        if (!is_string($rawValue)) {
            return null;
        }

        $formula = trim($rawValue);
        if (!str_starts_with(strtoupper($formula), '=HYPERLINK(')) {
            return null;
        }

        if (preg_match('/^=HYPERLINK\(\s*"([^"]+)"\s*[;,]/i', $formula, $matches)) {
            return trim((string) $matches[1]);
        }

        if (preg_match('/^=HYPERLINK\(\s*\'([^\']+)\'\s*[;,]/i', $formula, $matches)) {
            return trim((string) $matches[1]);
        }

        return null;
    }

    private function extractAllHyperlinksFromXml(string $filePath, int $sheetIndex = 0): array
    {
        $hyperlinks = [];

        try {
            $zip = new \ZipArchive();
            if ($zip->open($filePath) !== true) {
                return $hyperlinks;
            }

            // Find the sheet XML file
            $sheetFile = "xl/worksheets/sheet" . ($sheetIndex + 1) . ".xml";
            $relsFile  = "xl/worksheets/_rels/sheet" . ($sheetIndex + 1) . ".xml.rels";

            $sheetXml = $zip->getFromName($sheetFile);
            $relsXml  = $zip->getFromName($relsFile);
            $zip->close();

            if (!$sheetXml || !$relsXml) {
                return $hyperlinks;
            }

            // Parse relationships: map rId => URL
            $relsDoc = new \DOMDocument();
            @$relsDoc->loadXML($relsXml);
            $relationships = [];
            foreach ($relsDoc->getElementsByTagName('Relationship') as $rel) {
                $id     = $rel->getAttribute('Id');
                $target = $rel->getAttribute('Target');
                $type   = $rel->getAttribute('Type');
                if (str_contains($type, 'hyperlink')) {
                    $relationships[$id] = $target;
                }
            }

            if (empty($relationships)) {
                return $hyperlinks;
            }

            // Parse sheet XML: find <hyperlink> elements and map cell ref => URL
            $sheetDoc = new \DOMDocument();
            @$sheetDoc->loadXML($sheetXml);
            $ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
            $rNs = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

            foreach ($sheetDoc->getElementsByTagNameNS($ns, 'hyperlink') as $hl) {
                $ref = $hl->getAttribute('ref');
                $rId = $hl->getAttributeNS($rNs, 'id');

                if ($ref && $rId && isset($relationships[$rId])) {
                    // ref có thể là range vd "F2:F2", lấy ô đầu tiên
                    $cell = explode(':', $ref)[0];
                    $hyperlinks[$cell] = $relationships[$rId];
                }
            }

        } catch (\Throwable $e) {
            // Silently fail, return empty array
        }

        return $hyperlinks;
    }

    private function sanitizeExternalUrl($value): ?string
    {
        $cleaned = $this->cleanCellValue($value);
        if (empty($cleaned)) {
            return null;
        }

        $cleaned = trim($cleaned, " \t\n\r\0\x0B\"'");

        if (preg_match('/^=HYPERLINK\("([^\"]+)"/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        }

        if (preg_match('/^=HYPERLINK\(\s*\'([^\']+)\'\s*[;,]/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        }

        if (str_starts_with($cleaned, '//')) {
            $cleaned = 'https:' . $cleaned;
        }

        $cleaned = str_replace(' ', '%20', $cleaned);

        if (!preg_match('/^https?:\/\//i', $cleaned)) {
            return null;
        }

        return filter_var($cleaned, FILTER_VALIDATE_URL) ? $cleaned : null;
    }

    private function cleanCellValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $cleaned = trim(strip_tags((string)$value));

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

            $result = trim((string)$translator->translate($text));
            if (!empty($result)) {
                $translated = $result;
            }
        } catch (\Throwable $e) {
            Log::warning('Vocabulary translation fallback used', [
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
                'audio_url' => $word['audio_url'] ?? null,
                'collocation' => $word['collocation'] ?? null,
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
    private function convertGoogleDriveUrl(string $url, string $type = 'view'): string
    {
        // Match Google Drive file URL patterns
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $fileId = $matches[1];
            if ($type === 'audio') {
                return "https://drive.google.com/uc?export=download&id={$fileId}";
            }
            return "https://drive.google.com/uc?export=view&id={$fileId}";
        }
        return $url;
    }

    private function resolvePreferredWordListForStudent($user): array
    {
        $approvedSets = $this->getApprovedBundleVocabularySetsForUser($user);

        if ($approvedSets->isNotEmpty()) {
            $bundleWordList = $this->syncBundleVocabularyFlashcardsForUser($user, $approvedSets);

            if (!empty($bundleWordList)) {
                return [
                    'source' => 'bundle',
                    'wordList' => $bundleWordList,
                    'bundleSetIds' => $approvedSets->pluck('id')->toArray(),
                ];
            }
        }

        $myWordList = WordList::with('flashcards')->firstOrCreate(
            [
                'user_id' => $user->id,
                'category' => 'user',
                'name' => 'My Word List'
            ],
            [
                'description' => 'My personal vocabulary collection',
                'is_public' => false,
                'word_count' => 0
            ]
        );

        return [
            'source' => 'my',
            'wordList' => $myWordList,
            'bundleSetIds' => [],
        ];
    }

    private function getApprovedBundleVocabularySetsForUser($user)
    {
        $bundleIds = $user->getPurchasedBundlesIds();

        if (empty($bundleIds)) {
            return collect();
        }

        return BundleVocabularySet::query()
            ->approved()
            ->whereIn('bundle_id', $bundleIds)
            ->orderByDesc('approved_at')
            ->orderByDesc('id')
            ->get();
    }

    private function syncBundleVocabularyFlashcardsForUser($user, $approvedSets)
    {
        $setIds = $approvedSets->pluck('id')->toArray();
        if (empty($setIds)) {
            return null;
        }

        $words = BundleVocabularyWord::query()
            ->whereIn('vocabulary_set_id', $setIds)
            ->orderBy('vocabulary_set_id')
            ->orderBy('sort_order')
            ->get();

        if ($words->isEmpty()) {
            return null;
        }

        $bundleWordList = WordList::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'category' => 'bundle_auto',
                'name' => 'Bundle Vocabulary',
            ],
            [
                'description' => 'Vocabulary synced from approved bundles',
                'is_public' => false,
                'word_count' => 0,
            ]
        );

        $bundleTitleById = [];
        $bundles = Bundle::query()
            ->whereIn('id', $approvedSets->pluck('bundle_id')->toArray())
            ->get(['id', 'slug']);

        foreach ($bundles as $bundle) {
            $bundleTitleById[$bundle->id] = $bundle->title ?: $bundle->slug;
        }

        $bundleTitles = [];
        foreach ($approvedSets as $set) {
            if (!empty($bundleTitleById[$set->bundle_id])) {
                $bundleTitles[] = $bundleTitleById[$set->bundle_id];
            }
        }
        $bundleTitles = array_unique($bundleTitles);

        if (!empty($bundleTitles)) {
            $bundleWordList->description = trans('panel.bundle_vocabulary_auto_description', [
                'bundles' => implode(', ', $bundleTitles),
            ]);
        }

        $syncData = [];
        $order = 1;

        foreach ($words as $word) {
            $flashcard = Flashcard::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'word' => $word->word,
                    'part_of_speech' => $word->part_of_speech,
                ],
                [
                    'pronunciation' => $word->pronunciation,
                    'definition' => $word->definition ?: ($word->translation_vi ?: $word->word),
                    'example' => $word->example,
                    'translation' => $word->translation_vi,
                ]
            );

            $syncData[$flashcard->id] = ['order' => $order];
            $order++;
        }

        $bundleWordList->flashcards()->sync($syncData);
        $bundleWordList->updateWordCount();
        $bundleWordList->save();

        return $bundleWordList->load('flashcards');
    }
}