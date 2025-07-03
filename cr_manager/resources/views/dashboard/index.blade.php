@extends('layouts.app')

@section('title', 'Dashboard - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
                Dashboard
            </h1>
            <p class="text-gray-600">Welcome to your secure password manager</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Credentials -->
        <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-key text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Credentials
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_credentials'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('credentials.index') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        View all credentials
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Credit Cards -->
        <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-credit-card text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Credit Cards
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_credit_cards'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('credit-cards.index') }}" class="font-medium text-green-600 hover:text-green-500">
                        View all cards
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Secure Notes -->
        <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-sticky-note text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Secure Notes
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_secure_notes'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('secure-notes.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                        View all notes
                    </a>
                </div>
            </div>
        </div>

        <!-- Favorites -->
        <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-heart text-red-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Favorites
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $stats['total_favorites'] }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('credentials.index') }}?favorites=1" class="font-medium text-red-600 hover:text-red-500">
                        View favorites
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('credentials.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Credential
                </a>
                <a href="{{ route('credit-cards.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Credit Card
                </a>
                <a href="{{ route('secure-notes.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Secure Note
                </a>
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Category
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 