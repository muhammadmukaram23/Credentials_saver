<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';



    public function index()
    {
        try {
            // Fetch all data for dashboard
            $credentials = Http::get($this->apiBaseUrl . '/credentials');
            $creditCards = Http::get($this->apiBaseUrl . '/credit-cards');
            $secureNotes = Http::get($this->apiBaseUrl . '/secure-notes');
            $categories = Http::get($this->apiBaseUrl . '/categories');

            $stats = [
                'total_credentials' => $credentials->successful() ? count($credentials->json()) : 0,
                'total_credit_cards' => $creditCards->successful() ? count($creditCards->json()) : 0,
                'total_secure_notes' => $secureNotes->successful() ? count($secureNotes->json()) : 0,
                'total_favorites' => $credentials->successful() ? 
                    count(array_filter($credentials->json(), fn($c) => $c['is_favorite'])) : 0,
            ];

            return view('dashboard.index', compact('stats'));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Failed to load dashboard: ' . $e->getMessage()]);
        }
    }
} 