<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SecureNoteController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';



    public function index()
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/secure-notes');
            $secureNotes = $response->successful() ? $response->json() : [];

            return view('secure-notes.index', compact('secureNotes'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to load secure notes: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];
            return view('secure-notes.create', compact('categories'));
        } catch (\Exception $e) {
            return view('secure-notes.create', ['categories' => []]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:1',
            'tags' => 'nullable|max:500'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'tags' => $request->tags
            ];

            // Handle category - FastAPI expects category name, not ID
            if ($request->category_id) {
                try {
                    $categoryResponse = Http::get($this->apiBaseUrl . '/categories/' . $request->category_id);
                    if ($categoryResponse->successful()) {
                        $category = $categoryResponse->json();
                        $data['category'] = $category['name'];
                    }
                } catch (\Exception $e) {
                    // If category fetch fails, continue without category
                }
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->post($this->apiBaseUrl . '/secure-notes', $data);

            if ($response->successful()) {
                return redirect()->route('secure-notes.index')->with('success', 'Secure note created successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to create secure note']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->get($this->apiBaseUrl . '/secure-notes/' . $id);
            
            if ($response->successful()) {
                $secureNote = $response->json();
                return view('secure-notes.show', compact('secureNote'));
            } else {
                return redirect()->route('secure-notes.index')->withErrors(['error' => 'Secure note not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('secure-notes.index')->withErrors(['error' => 'Failed to load secure note: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->get($this->apiBaseUrl . '/secure-notes/' . $id);
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            
            if ($response->successful()) {
                $secureNote = $response->json();
                $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];
                return view('secure-notes.edit', compact('secureNote', 'categories'));
            } else {
                return redirect()->route('secure-notes.index')->withErrors(['error' => 'Secure note not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('secure-notes.index')->withErrors(['error' => 'Failed to load secure note: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:1',
            'tags' => 'nullable|max:500'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $data = [
                'title' => $request->title,
                'content' => $request->content,
                'tags' => $request->tags
            ];

            // Handle category - FastAPI expects category name, not ID
            if ($request->category_id) {
                try {
                    $categoryResponse = Http::get($this->apiBaseUrl . '/categories/' . $request->category_id);
                    if ($categoryResponse->successful()) {
                        $category = $categoryResponse->json();
                        $data['category'] = $category['name'];
                    }
                } catch (\Exception $e) {
                    // If category fetch fails, continue without category
                }
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->put($this->apiBaseUrl . '/secure-notes/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('secure-notes.show', $id)->with('success', 'Secure note updated successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to update secure note']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete($this->apiBaseUrl . '/secure-notes/' . $id);
            
            if ($response->successful()) {
                return redirect()->route('secure-notes.index')->with('success', 'Secure note deleted successfully!');
            } else {
                return redirect()->route('secure-notes.index')->withErrors(['error' => 'Failed to delete secure note']);
            }
        } catch (\Exception $e) {
            return redirect()->route('secure-notes.index')->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }
} 