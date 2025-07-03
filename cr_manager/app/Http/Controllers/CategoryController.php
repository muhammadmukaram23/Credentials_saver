<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';



    public function index()
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/categories');
            $categories = $response->successful() ? $response->json() : [];

            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to load categories: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'icon' => 'nullable|max:50'
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/categories', [
                'name' => $request->name,
                'description' => $request->description,
                'icon' => $request->icon
            ]);

            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Category created successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to create category']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/categories/' . $id);
            
            if ($response->successful()) {
                $category = $response->json();
                
                // Get statistics for this category
                $stats = [
                    'credentials' => 0,
                    'credit_cards' => 0,
                    'secure_notes' => 0
                ];
                
                // Try to get counts from API (if endpoints exist)
                try {
                    $credentialsResponse = Http::get($this->apiBaseUrl . '/credentials', ['category_id' => $id]);
                    if ($credentialsResponse->successful()) {
                        $stats['credentials'] = count($credentialsResponse->json());
                    }
                    
                    $creditCardsResponse = Http::get($this->apiBaseUrl . '/credit-cards', ['category_id' => $id]);
                    if ($creditCardsResponse->successful()) {
                        $stats['credit_cards'] = count($creditCardsResponse->json());
                    }
                    
                    $secureNotesResponse = Http::get($this->apiBaseUrl . '/secure-notes', ['category_id' => $id]);
                    if ($secureNotesResponse->successful()) {
                        $stats['secure_notes'] = count($secureNotesResponse->json());
                    }
                } catch (\Exception $e) {
                    // If category-specific endpoints don't exist, use default stats
                }
                
                return view('categories.show', compact('category', 'stats'));
            } else {
                return redirect()->route('categories.index')->withErrors(['error' => 'Category not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->withErrors(['error' => 'Failed to load category: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/categories/' . $id);
            
            if ($response->successful()) {
                $category = $response->json();
                return view('categories.edit', compact('category'));
            } else {
                return redirect()->route('categories.index')->withErrors(['error' => 'Category not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->withErrors(['error' => 'Failed to load category: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'icon' => 'nullable|max:50'
        ]);

        try {
            $response = Http::put($this->apiBaseUrl . '/categories/' . $id, [
                'name' => $request->name,
                'description' => $request->description,
                'icon' => $request->icon
            ]);

            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to update category']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete($this->apiBaseUrl . '/categories/' . $id);
            
            if ($response->successful()) {
                return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
            } else {
                return redirect()->route('categories.index')->withErrors(['error' => 'Failed to delete category']);
            }
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }
} 