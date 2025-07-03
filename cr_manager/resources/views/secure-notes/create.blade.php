@extends('layouts.app')

@section('title', 'Add Secure Note - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <a href="{{ route('secure-notes.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-plus text-yellow-600 mr-2"></i>
                        Add New Secure Note
                    </h1>
                    <p class="text-gray-600">Create a new secure note</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <form action="{{ route('secure-notes.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title *
                    </label>
                    <input type="text" name="title" id="title" required
                           value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                           placeholder="Enter note title">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <input type="text" name="category" id="category"
                           value="{{ old('category') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                           placeholder="e.g., Personal, Work, Financial">
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags
                    </label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                           placeholder="e.g., important, backup, recovery">
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Content *
                    </label>
                    <textarea name="content" id="content" rows="10" required
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                              placeholder="Enter your secure note content here...">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('secure-notes.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Secure Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 