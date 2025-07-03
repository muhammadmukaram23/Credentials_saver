@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Create New Category</h1>
            <a href="{{ route('categories.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Enter category name"
                           required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter category description"></textarea>
                </div>

                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                        Icon
                    </label>
                    <div class="grid grid-cols-6 gap-2 mb-3">
                        <button type="button" onclick="selectIcon('fa-folder')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-folder text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-briefcase')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-briefcase text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-home')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-home text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-globe')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-globe text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-shopping-cart')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-gamepad')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-gamepad text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-university')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-university text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-heart')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-heart text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-star')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-star text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-cog')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-cog text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-shield-alt')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </button>
                        <button type="button" onclick="selectIcon('fa-tag')" class="icon-btn p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                            <i class="fas fa-tag text-xl"></i>
                        </button>
                    </div>
                    <input type="hidden" id="icon" name="icon" value="fa-folder">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Selected:</span>
                        <i id="selectedIcon" class="fas fa-folder text-blue-600"></i>
                        <span id="selectedIconName" class="text-sm text-gray-600">fa-folder</span>
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
                    <i class="fas fa-save mr-2"></i>Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function selectIcon(iconClass) {
    // Remove selected class from all buttons
    document.querySelectorAll('.icon-btn').forEach(btn => {
        btn.classList.remove('bg-blue-100', 'border-blue-500');
    });
    
    // Add selected class to clicked button
    event.target.closest('.icon-btn').classList.add('bg-blue-100', 'border-blue-500');
    
    // Update hidden input and display
    document.getElementById('icon').value = iconClass;
    document.getElementById('selectedIcon').className = `fas ${iconClass} text-blue-600`;
    document.getElementById('selectedIconName').textContent = iconClass;
}

// Form will submit normally to Laravel route

// Initialize first icon as selected
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.icon-btn').classList.add('bg-blue-100', 'border-blue-500');
});
</script>
@endsection 