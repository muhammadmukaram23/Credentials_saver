<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $apiBaseUrl = 'http://127.0.0.1:8001/api/v1';

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'master_password' => 'required|min:8'
        ]);

        try {
            $response = Http::post($this->apiBaseUrl . '/auth/login', [
                'master_password' => $request->master_password
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Session::put('authenticated', true);
                Session::put('access_token', $data['access_token']);
                Session::put('master_password', $request->master_password);
                
                return redirect()->route('dashboard')->with('success', 'Successfully logged in!');
            } else {
                $error = $response->json();
                return back()->withErrors(['master_password' => $error['detail'] ?? 'Login failed']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['master_password' => 'Connection error: ' . $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            Http::post($this->apiBaseUrl . '/auth/logout');
        } catch (\Exception $e) {
            // Log error but continue with logout
        }

        Session::flush();
        return redirect()->route('login')->with('success', 'Successfully logged out!');
    }

    public function checkAuth()
    {
        if (!Session::has('authenticated')) {
            return redirect()->route('login');
        }
        return null;
    }
} 