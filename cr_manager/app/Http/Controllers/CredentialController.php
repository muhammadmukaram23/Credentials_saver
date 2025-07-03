<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CredentialController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';



    public function index()
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/credentials');
            $credentials = $response->successful() ? $response->json() : [];
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];

            return view('credentials.index', compact('credentials', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to load credentials: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/categories');
            $categories = $response->successful() ? $response->json() : [];
            
            return view('credentials.create', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Failed to load categories: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|max:255',
            'category_id' => 'required|integer',
            'password' => 'required|min:1',
            'username' => 'nullable|max:255',
            'email' => 'nullable|email',
            'website_url' => 'nullable|url|max:500',
            'notes' => 'nullable',
            'is_favorite' => 'boolean'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->post($this->apiBaseUrl . '/credentials', [
                'service_name' => $request->service_name,
                'category_id' => $request->category_id,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'website_url' => $request->website_url,
                'notes' => $request->notes,
                'is_favorite' => $request->has('is_favorite')
            ]);

            if ($response->successful()) {
                return redirect()->route('credentials.index')->with('success', 'Credential created successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to create credential']);
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
            ])->get($this->apiBaseUrl . '/credentials/' . $id);
            
            if ($response->successful()) {
                $credential = $response->json();
                return view('credentials.show', compact('credential'));
            } else {
                return redirect()->route('credentials.index')->withErrors(['error' => 'Credential not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Failed to load credential: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $credentialResponse = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->get($this->apiBaseUrl . '/credentials/' . $id);
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            
            if ($credentialResponse->successful() && $categoriesResponse->successful()) {
                $credential = $credentialResponse->json();
                $categories = $categoriesResponse->json();
                return view('credentials.edit', compact('credential', 'categories'));
            } else {
                return redirect()->route('credentials.index')->withErrors(['error' => 'Credential not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Failed to load credential: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|max:255',
            'category_id' => 'required|integer',
            'username' => 'nullable|max:255',
            'email' => 'nullable|email',
            'password' => 'nullable|min:1',
            'website_url' => 'nullable|url|max:500',
            'notes' => 'nullable',
            'is_favorite' => 'boolean'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $data = [
                'service_name' => $request->service_name,
                'category_id' => $request->category_id,
                'username' => $request->username,
                'email' => $request->email,
                'website_url' => $request->website_url,
                'notes' => $request->notes,
                'is_favorite' => $request->has('is_favorite')
            ];

            if ($request->password) {
                $data['password'] = $request->password;
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->put($this->apiBaseUrl . '/credentials/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('credentials.index')->with('success', 'Credential updated successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to update credential']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete($this->apiBaseUrl . '/credentials/' . $id);
            
            if ($response->successful()) {
                return redirect()->route('credentials.index')->with('success', 'Credential deleted successfully!');
            } else {
                return redirect()->route('credentials.index')->withErrors(['error' => 'Failed to delete credential']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('credentials.index');
        }

        try {
            $response = Http::get($this->apiBaseUrl . '/credentials/search/' . urlencode($query));
            $credentials = $response->successful() ? $response->json() : [];
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];

            return view('credentials.index', compact('credentials', 'categories', 'query'));
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Search failed: ' . $e->getMessage()]);
        }
    }

    public function byCategory($categoryId)
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/categories/' . $categoryId . '/credentials');
            $credentials = $response->successful() ? $response->json() : [];
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];

            return view('credentials.index', compact('credentials', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('credentials.index')->withErrors(['error' => 'Failed to load credentials: ' . $e->getMessage()]);
        }
    }
} 