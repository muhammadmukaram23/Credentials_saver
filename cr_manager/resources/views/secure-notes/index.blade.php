@extends('layouts.app')

@section('title', 'Secure Notes - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                        Secure Notes
                    </h1>
                    <p class="text-gray-600">Store sensitive information securely</p>
                </div>
                <a href="{{ route('secure-notes.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Secure Note
                </a>
            </div>
        </div>
    </div>

    <!-- Secure Notes List -->
    @if(count($secureNotes) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($secureNotes as $note)
                <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $note['title'] ?? 'Untitled Note' }}</h3>
                                @if(isset($note['category']) && $note['category'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-folder mr-1"></i>
                                        {{ $note['category'] }}
                                    </p>
                                @endif
                                @if(isset($note['tags']) && $note['tags'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-tags mr-1"></i>
                                        {{ $note['tags'] }}
                                    </p>
                                @endif
                                @if(isset($note['content']) && $note['content'])
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-3">
                                        {{ Str::limit(strip_tags($note['content']), 100) }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    Updated {{ date('M j, Y', strtotime($note['updated_at'])) }}
                                </p>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex space-x-2">
                                    <a href="{{ route('secure-notes.show', $note['id']) }}" 
                                       class="text-yellow-600 hover:text-yellow-500" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('secure-notes.edit', $note['id']) }}" 
                                       class="text-blue-600 hover:text-blue-500" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('secure-notes.destroy', $note['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmDelete('Are you sure you want to delete this secure note?')" 
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
                <i class="fas fa-sticky-note text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No secure notes found</h3>
                <p class="text-gray-600 mb-6">Get started by adding your first secure note.</p>
                <a href="{{ route('secure-notes.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Your First Secure Note
                </a>
            </div>
        </div>
    @endif
</div>
@endsection 