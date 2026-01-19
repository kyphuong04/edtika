<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $this->authorize('admin_dictionary');

        $data = [
            'pageTitle' => trans('admin/main.dictionary_and_flashcard'),
            'hasApiKey' => !empty($this->accessKey),
        ];

        return view('admin.dictionary.index', $data);
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
}
