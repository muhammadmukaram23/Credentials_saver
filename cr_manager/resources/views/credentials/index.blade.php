@extends('layouts.app')

@section('title', 'Credentials - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-key text-indigo-600 mr-2"></i>
                        Credentials
                        @if(isset($query))
                            <span class="text-lg text-gray-600">- Search results for "{{ $query }}"</span>
                        @endif
                    </h1>
                    <p class="text-gray-600">Manage your passwords and login credentials</p>
                </div>
                <a href="{{ route('credentials.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Credential
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <form action="{{ route('credentials.search') }}" method="GET" class="flex">
                        <input type="text" name="q" placeholder="Search credentials..." value="{{ request('q') }}" 
                               class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Category Filter -->
                @if(isset($categories) && count($categories) > 0)
                <div class="md:w-64">
                    <select onchange="window.location.href = this.value" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="{{ route('credentials.index') }}">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ route('credentials.by-category', $category['id']) }}">
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Credentials List -->
    @if(count($credentials) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($credentials as $credential)
            
                <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    {{ $credential['service_name'] ?? 'Unnamed Service' }}
                                    @if(isset($credential['is_favorite']) && $credential['is_favorite'])
                                        <i class="fas fa-heart text-red-500 ml-2"></i>
                                    @endif
                                </h3>
                                
                                @if(isset($credential['username']) && $credential['username'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $credential['username'] }}
                                    </p>
                                @endif
                                
                                @if(isset($credential['email']) && $credential['email'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-envelope mr-1"></i>
                                        {{ $credential['email'] }}
                                    </p>
                                @endif
                                
                                @if(isset($credential['website_url']) && $credential['website_url'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-globe mr-1"></i>
                                        <a href="{{ $credential['website_url'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                                            {{ parse_url($credential['website_url'], PHP_URL_HOST) ?: $credential['website_url'] }}
                                        </a>
                                    </p>
                                @endif
                                
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    Updated {{ date('M j, Y', strtotime($credential['updated_at'])) }}
                                </p>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex space-x-2">
                                    @if(isset($credential['password']) && $credential['password'])
                                        <button onclick="copyToClipboard('{{ $credential['password'] }}')" 
                                                class="text-gray-400 hover:text-gray-600" title="Copy Password">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('credentials.show', $credential['id']) }}" 
                                       class="text-indigo-600 hover:text-indigo-500" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('credentials.edit', $credential['id']) }}" 
                                       class="text-yellow-600 hover:text-yellow-500" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('credentials.destroy', $credential['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmDelete('Are you sure you want to delete this credential?')" 
                                                class="text-red-600 hover:text-red-500" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-12 sm:px-6 lg:px-8 text-center">
                <i class="fas fa-key text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No credentials found</h3>
                <p class="text-gray-600 mb-6">
                    @if(isset($query))
                        No credentials match your search criteria.
                    @else
                        Get started by adding your first credential.
                    @endif
                </p>
                @if(!isset($query))
                    <a href="{{ route('credentials.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add Your First Credential
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection 