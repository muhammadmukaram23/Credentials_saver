@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $category['name'] ?? 'Category' }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('categories.edit', $category['id'] ?? '') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas {{ $category['icon'] ?? 'fa-folder' }} text-2xl text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ $category['name'] ?? 'Unnamed Category' }}</h2>
                            <p class="text-gray-600">{{ $category['description'] ?? 'No description available' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-key text-blue-600 mr-3"></i>
                            <div>
                                <div class="text-lg font-semibold text-blue-800">{{ $stats['credentials'] ?? 0 }}</div>
                                <div class="text-sm text-blue-600">Credentials</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-credit-card text-green-600 mr-3"></i>
                            <div>
                                <div class="text-lg font-semibold text-green-800">{{ $stats['credit_cards'] ?? 0 }}</div>
                                <div class="text-sm text-green-600">Credit Cards</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-sticky-note text-purple-600 mr-3"></i>
                            <div>
                                <div class="text-lg font-semibold text-purple-800">{{ $stats['secure_notes'] ?? 0 }}</div>
                                <div class="text-sm text-purple-600">Secure Notes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Metadata -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Information</h4>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <div class="font-medium">{{ isset($category['created_at']) ? date('M j, Y g:i A', strtotime($category['created_at'])) : 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Modified:</span>
                            <div class="font-medium">{{ isset($category['updated_at']) ? date('M j, Y g:i A', strtotime($category['updated_at'])) : 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white border rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Actions</h4>
                    <div class="space-y-2">
                        <a href="{{ route('credentials.index', ['category' => $category['id'] ?? '']) }}" 
                           class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors block text-center">
                            <i class="fas fa-key mr-2"></i>View Credentials
                        </a>
                        <a href="{{ route('credit-cards.index', ['category' => $category['id'] ?? '']) }}" 
                           class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors block text-center">
                            <i class="fas fa-credit-card mr-2"></i>View Credit Cards
                        </a>
                        <a href="{{ route('secure-notes.index', ['category' => $category['id'] ?? '']) }}" 
                           class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors block text-center">
                            <i class="fas fa-sticky-note mr-2"></i>View Notes
                        </a>
                        <form method="POST" action="{{ route('categories.destroy', $category['id'] ?? '') }}" 
                              onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')" 
                              class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 