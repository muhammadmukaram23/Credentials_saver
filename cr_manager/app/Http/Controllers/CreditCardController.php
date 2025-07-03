<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CreditCardController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';



    public function index()
    {
        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->get($this->apiBaseUrl . '/credit-cards');
            
            $creditCards = $response->successful() ? $response->json() : [];

            return view('credit-cards.index', compact('creditCards'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to load credit cards: ' . $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];
            return view('credit-cards.create', compact('categories'));
        } catch (\Exception $e) {
            return view('credit-cards.create', ['categories' => []]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_name' => 'required|max:255',
            'cardholder_name' => 'required|max:255',
            'card_number' => 'required|min:13|max:19',
            'expiry_month' => 'required|integer|min:1|max:12',
            'expiry_year' => 'required|integer|min:2024',
            'cvv' => 'required|min:3|max:4',
            'card_type' => 'required|in:visa,mastercard,amex,discover,other',
            'bank_name' => 'nullable|max:255',
            'billing_address' => 'nullable',
            'notes' => 'nullable'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $data = [
                'card_name' => $request->card_name,
                'cardholder_name' => $request->cardholder_name,
                'card_number' => str_replace(' ', '', $request->card_number),
                'expiry_month' => $request->expiry_month,
                'expiry_year' => $request->expiry_year,
                'cvv' => $request->cvv,
                'bank_name' => $request->bank_name,
                'card_type' => $request->card_type,
                'billing_address' => $request->billing_address,
                'notes' => $request->notes
            ];

            if ($request->category_id) {
                $data['category_id'] = (int)$request->category_id;
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->post($this->apiBaseUrl . '/credit-cards', $data);

            if ($response->successful()) {
                return redirect()->route('credit-cards.index')->with('success', 'Credit card created successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to create credit card']);
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
            ])->get($this->apiBaseUrl . '/credit-cards/' . $id);
            
            if ($response->successful()) {
                $creditCard = $response->json();
                return view('credit-cards.show', compact('creditCard'));
            } else {
                return redirect()->route('credit-cards.index')->withErrors(['error' => 'Credit card not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credit-cards.index')->withErrors(['error' => 'Failed to load credit card: ' . $e->getMessage()]);
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
            ])->get($this->apiBaseUrl . '/credit-cards/' . $id);
            
            $categoriesResponse = Http::get($this->apiBaseUrl . '/categories');
            
            if ($response->successful()) {
                $creditCard = $response->json();
                $categories = $categoriesResponse->successful() ? $categoriesResponse->json() : [];
                return view('credit-cards.edit', compact('creditCard', 'categories'));
            } else {
                return redirect()->route('credit-cards.index')->withErrors(['error' => 'Credit card not found']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credit-cards.index')->withErrors(['error' => 'Failed to load credit card: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'card_name' => 'required|max:255',
            'cardholder_name' => 'required|max:255',
            'card_number' => 'nullable|min:13|max:19',
            'expiry_month' => 'required|integer|min:1|max:12',
            'expiry_year' => 'required|integer|min:2024',
            'cvv' => 'nullable|min:3|max:4',
            'card_type' => 'nullable|in:visa,mastercard,amex,discover,other',
            'bank_name' => 'nullable|max:255',
            'billing_address' => 'nullable',
            'notes' => 'nullable'
        ]);

        try {
            $masterPassword = Session::get('master_password');
            if (!$masterPassword) {
                return redirect()->route('login')->withErrors(['error' => 'Please login again']);
            }

            $data = [
                'card_name' => $request->card_name,
                'cardholder_name' => $request->cardholder_name,
                'expiry_month' => $request->expiry_month,
                'expiry_year' => $request->expiry_year,
                'bank_name' => $request->bank_name,
                'card_type' => $request->card_type ?: 'other',
                'billing_address' => $request->billing_address,
                'notes' => $request->notes
            ];

            if ($request->card_number) {
                $data['card_number'] = str_replace(' ', '', $request->card_number);
            }

            if ($request->cvv) {
                $data['cvv'] = $request->cvv;
            }

            if ($request->category_id) {
                $data['category_id'] = (int)$request->category_id;
            }

            $response = Http::withHeaders([
                'X-Master-Password' => $masterPassword
            ])->put($this->apiBaseUrl . '/credit-cards/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('credit-cards.show', $id)->with('success', 'Credit card updated successfully!');
            } else {
                $error = $response->json();
                return back()->withErrors(['error' => $error['detail'] ?? 'Failed to update credit card']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete($this->apiBaseUrl . '/credit-cards/' . $id);
            
            if ($response->successful()) {
                return redirect()->route('credit-cards.index')->with('success', 'Credit card deleted successfully!');
            } else {
                return redirect()->route('credit-cards.index')->withErrors(['error' => 'Failed to delete credit card']);
            }
        } catch (\Exception $e) {
            return redirect()->route('credit-cards.index')->withErrors(['error' => 'Connection error: ' . $e->getMessage()]);
        }
    }
} 