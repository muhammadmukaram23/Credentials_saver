@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Secure Note</h1>
            <a href="{{ route('secure-notes.show', $secureNote['id'] ?? '') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <form id="secureNoteForm" method="POST" action="{{ route('secure-notes.update', $secureNote['id'] ?? '') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ $secureNote['title'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Enter note title"
                               required>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Content <span class="text-red-500">*</span>
                        </label>
                        <textarea id="content" 
                                  name="content" 
                                  rows="15" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter your secure note content here..."
                                  required>{{ $secureNote['content'] ?? '' }}</textarea>
                        <div class="mt-2 text-sm text-gray-500">
                            <span id="characterCount">0</span> characters
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Category</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}" {{ (isset($secureNote['category_id']) && $secureNote['category_id'] == $category['id']) ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" 
                               id="tags" 
                               name="tags" 
                               value="{{ $secureNote['tags'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="tag1, tag2, tag3">
                        <div class="mt-1 text-xs text-gray-500">
                            Separate tags with commas
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Note Information</h4>
                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-600">Created:</span>
                                <div class="font-medium">{{ isset($secureNote['created_at']) ? date('M j, Y g:i A', strtotime($secureNote['created_at'])) : 'N/A' }}</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Last Modified:</span>
                                <div class="font-medium">{{ isset($secureNote['updated_at']) ? date('M j, Y g:i A', strtotime($secureNote['updated_at'])) : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-shield-alt mr-2"></i>Security Note
                        </h4>
                        <p class="text-sm text-blue-700">
                            This note is encrypted and stored securely. Only you can access its contents.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" 
                        onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update Note
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter
const contentTextarea = document.getElementById('content');
const characterCount = document.getElementById('characterCount');

function updateCharacterCount() {
    const count = contentTextarea.value.length;
    characterCount.textContent = count.toLocaleString();
}

contentTextarea.addEventListener('input', updateCharacterCount);
updateCharacterCount(); // Initial count

// Form will submit normally to Laravel route

// Auto-save functionality (optional)
let autoSaveTimer;
function autoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;
        
        if (title && content) {
            // Save to localStorage as backup
            localStorage.setItem('secureNote_{{ $secureNote["id"] ?? "" }}_backup', JSON.stringify({
                title: title,
                content: content,
                timestamp: new Date().toISOString()
            }));
        }
    }, 5000); // Auto-save every 5 seconds
}

contentTextarea.addEventListener('input', autoSave);
document.getElementById('title').addEventListener('input', autoSave);
</script>
@endsection 