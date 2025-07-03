@extends('layouts.app')

@section('title', 'Login - Password Manager')

@section('content')
<div class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center">
                <i class="fas fa-shield-alt text-indigo-600 text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Password Manager
            </h2>
            <p class="mt-2 text-center text-sm text-gray-200">
                Enter your master password to access your vault
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="relative">
                    <label for="master_password" class="sr-only">Master Password</label>
                    <input id="master_password" name="master_password" type="password" required 
                           class="appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="Master Password">
                    <button type="button" onclick="togglePasswordVisibility(this)" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-eye text-gray-400"></i>
                    </button>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-indigo-500 group-hover:text-indigo-400"></i>
                    </span>
                    Sign In
                </button>
            </div>
        </form>
        
        <div class="text-center">
            <p class="text-sm text-gray-200">
                Secure access to your passwords and sensitive data
            </p>
        </div>
    </div>
</div>
@endsection 