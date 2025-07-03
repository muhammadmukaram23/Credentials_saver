@extends('layouts.app')

@section('title', 'Categories - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-folder text-purple-600 mr-2"></i>
                        Categories
                    </h1>
                    <p class="text-gray-600">Organize your credentials with categories</p>
                </div>
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Category
                </a>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    @if(count($categories) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    @if(isset($category['icon']) && $category['icon'])
                                        <i class="{{ $category['icon'] }} mr-2"></i>
                                    @else
                                        <i class="fas fa-folder mr-2"></i>
                                    @endif
                                    {{ $category['name'] ?? 'Unnamed Category' }}
                                </h3>
                                @if(isset($category['description']) && $category['description'])
                                    <p class="text-sm text-gray-600 mt-1">{{ $category['description'] }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    Created {{ date('M j, Y', strtotime($category['created_at'])) }}
                                </p>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex space-x-2">
                                    <a href="{{ route('categories.show', $category['id']) }}" 
                                       class="text-purple-600 hover:text-purple-500" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('categories.edit', $category['id']) }}" 
                                       class="text-yellow-600 hover:text-yellow-500" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmDelete('Are you sure you want to delete this category?')" 
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
                <i class="fas fa-folder text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                <p class="text-gray-600 mb-6">Get started by creating your first category.</p>
                <a href="{{ route('categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Category
                </a>
            </div>
        </div>
    @endif
</div>
@endsection