<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DebugController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';

    public function testApi()
    {
        if (!Session::has('authenticated')) {
            return redirect()->route('login')->withErrors(['error' => 'Please login first']);
        }

        try {
            $results = [];
            
            // Test credentials endpoint
            $credentialsResponse = Http::get($this->apiBaseUrl . '/credentials');
            $results['credentials'] = [
                'status' => $credentialsResponse->status(),
                'success' => $credentialsResponse->successful(),
                'data' => $credentialsResponse->successful() ? $credentialsResponse->json() : null,
                'error' => $credentialsResponse->successful() ? null : $credentialsResponse->body()
            ];

            // Test credit cards endpoint
            $creditCardsResponse = Http::get($this->apiBaseUrl . '/credit-cards');
            $results['credit-cards'] = [
                'status' => $creditCardsResponse->status(),
                'success' => $creditCardsResponse->successful(),
                'data' => $creditCardsResponse->successful() ? $creditCardsResponse->json() : null,
                'error' => $creditCardsResponse->successful() ? null : $creditCardsResponse->body()
            ];

            // Test secure notes endpoint
            $secureNotesResponse = Http::get($this->apiBaseUrl . '/secure-notes');
            $results['secure-notes'] = [
                'status' => $secureNotesResponse->status(),
                'success' => $secureNotesResponse->successful(),
                'data' => $secureNotesResponse->successful() ? $secureNotesResponse->json() : null,
                'error' => $secureNotesResponse->successful() ? null : $secureNotesResponse->body()
            ];

            // Test categories endpoint
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $results['categories'] = [
                'status' => $categoriesResponse->status(),
                'success' => $categoriesResponse->successful(),
                'data' => $categoriesResponse->successful() ? $categoriesResponse->json() : null,
                'error' => $categoriesResponse->successful() ? null : $categoriesResponse->body()
            ];

            return response()->json($results, 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Connection failed',
                'message' => $e->getMessage()
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }
} 