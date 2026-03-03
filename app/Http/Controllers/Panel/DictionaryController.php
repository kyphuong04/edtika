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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DictionaryController extends Controller
{
    // Using Free Dictionary API - free, no key required, has UK + US pronunciation audio
    private $freeDictBaseUrl = 'https://api.dictionaryapi.dev/api/v2/entries/en';

    public function index()
    {
        $user = Auth::user();

        // Get Academic Word Lists (Band-based) that user has access to
        $academicWordLists = AcademicWordList::with(['words', 'accessUsers'])
            ->where('is_active', true)
            ->get()
            ->map(function($list) use ($user) {
                $hasAccess = $list->hasAccess($user->id);
                return [
                    'id' => $list->id,
                    'name' => $list->name,
                    'description' => $list->description,
                    'band_level' => $list->band_level,
                    'word_count' => $list->word_count,
                    'has_access' => $hasAccess,
                    'is_locked' => !$hasAccess,
                ];
            });

        // Get user's personal "My Word List"
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

        // Get user stats
        $userStats = [
            'streak' => $this->getUserStreak($user->id),
            'ranking' => 0, // TODO: Implement ranking logic
            'band_estimate' => $user->band_estimate ?? 5.0,
        ];

        $data = [
            'pageTitle' => trans('panel.dictionary_and_flashcard'),
            'hasApiKey' => true, // Free Dictionary API – no key required
            'academicWordLists' => $academicWordLists,
            'myWordList' => $myWordList,
            'userStats' => $userStats,
            'authUser' => $user,
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
        $flashcards = Flashcard::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flashcards
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
            'flashcard_ids.*' => 'exists:flashcards,id',
        ]);

        $user = Auth::user();
        
        // Get My Word List
        $myWordList = WordList::where('user_id', $user->id)
            ->where('category', 'user')
            ->where('name', 'My Word List')
            ->with('flashcards')
            ->first();

        if (!$myWordList) {
            return response()->json([
                'success' => false,
                'message' => 'My Word List not found'
            ], 404);
        }

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
        foreach ($practiceFlashcards as $flashcard) {
            // Get 3 random wrong answers from My Word List
            $wrongAnswers = $myWordList->flashcards
                ->where('id', '!=', $flashcard->id)
                ->random(min(3, $myWordList->flashcards->count() - 1))
                ->pluck('word')
                ->toArray();

            // Combine with correct answer and shuffle
            $answers = array_merge([$flashcard->word], $wrongAnswers);
            shuffle($answers);

            $questions[] = [
                'flashcard_id' => $flashcard->id,
                'question' => $flashcard->definition,
                'correct_answer' => $flashcard->word,
                'answers' => $answers,
            ];
        }

        // Shuffle questions
        shuffle($questions);

        return response()->json([
            'success' => true,
            'data' => [
                'word_list_id' => $myWordList->id,
                'word_list_name' => $myWordList->name,
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
            'flashcard_id' => 'nullable|exists:flashcards,id',
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
                
                // Mark as learned if correct_count >= 3
                if ($progress->correct_count >= 3 && !$progress->is_learned) {
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
                
                // Mark as learned if correct_count >= 3
                if ($progress->correct_count >= 3 && !$progress->is_learned) {
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
        
        $myWordList = WordList::where('user_id', $user->id)
            ->where('category', 'user')
            ->where('name', 'My Word List')
            ->with('flashcards')
            ->first();

        if (!$myWordList) {
            return response()->json([
                'success' => false,
                'message' => 'My Word List not found'
            ], 404);
        }

        // Get user progress for each flashcard
        $flashcards = $myWordList->flashcards->map(function($flashcard) use ($user) {
            $progress = UserWordProgress::where('user_id', $user->id)
                ->where('flashcard_id', $flashcard->id)
                ->first();

            return [
                'id' => $flashcard->id,
                'word' => $flashcard->word,
                'pronunciation' => $flashcard->pronunciation,
                'definition' => $flashcard->definition,
                'example' => $flashcard->example,
                'translation' => $flashcard->translation,
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
                'flashcards' => $flashcards,
            ]
        ]);
    }
}