<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Flashcard;
use App\Models\WordList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DictionaryController extends Controller
{
    private $baseUrl = 'https://dictionary.cambridge.org/api/v1';
    private $accessKey;

    public function __construct()
    {
        // You need to set your Cambridge Dictionary API access key in .env file
        $this->accessKey = env('CAMBRIDGE_DICT_ACCESS_KEY', '');
    }

    public function index()
    {
        $data = [
            'pageTitle' => trans('panel.dictionary_and_flashcard'),
            'hasApiKey' => !empty($this->accessKey),
        ];

        return view('design_1.panel.dictionary.index', $data);
    }

    /**
     * Get available dictionaries
     */
    public function getDictionaries()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . '/dictionaries');

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . "/dictionaries/{$dictionaryCode}/search", [
                'q' => $query,
                'page' => $page,
                'pagesize' => $pageSize
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get best matching entry
     */
    public function searchFirst(Request $request)
    {
        $dictionaryCode = $request->get('dictionary', 'english-vietnamese');
        $query = $request->get('q', '');
        $format = $request->get('format', 'html');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query is required'
            ], 400);
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . "/dictionaries/{$dictionaryCode}/search/first", [
                'q' => $query,
                'format' => $format
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get spell checking suggestions (Did you mean?)
     */
    public function didYouMean(Request $request)
    {
        $dictionaryCode = $request->get('dictionary', 'english-vietnamese');
        $query = $request->get('q', '');
        $maxResults = $request->get('max', 5);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query is required'
            ], 400);
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . "/dictionaries/{$dictionaryCode}/search/didyoumean", [
                'q' => $query,
                'max' => $maxResults
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . "/dictionaries/{$dictionaryCode}/entries/{$entryId}/nearbyentries", [
                'max' => $max
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'accessKey' => $this->accessKey
            ])->get($this->baseUrl . "/dictionaries/{$dictionaryCode}/entries/{$entryId}", [
                'format' => $format
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
        ]);

        $flashcard = Flashcard::updateOrCreate(
            [
                'user_id' => $user->id,
                'word' => $request->word
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
}

