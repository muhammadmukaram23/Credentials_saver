@extends('layouts.app')

@section('title', 'Edit Credential - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <a href="{{ route('credentials.show', $credential['id']) }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-edit text-yellow-600 mr-2"></i>
                        Edit Credential
                    </h1>
                    <p class="text-gray-600">Update {{ $credential['service_name'] }} credentials</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <form action="{{ route('credentials.update', $credential['id']) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Name -->
                <div class="md:col-span-2">
                    <label for="service_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Name *
                    </label>
                    <input type="text" name="service_name" id="service_name" required
                           value="{{ old('service_name', $credential['service_name']) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="e.g., Gmail, Facebook, GitHub">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Category *
                    </label>
                    <select name="category_id" id="category_id" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}" 
                                {{ old('category_id', $credential['category_id']) == $category['id'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <input type="text" name="username" id="username"
                           value="{{ old('username', $credential['username']) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Enter username">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $credential['email']) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Enter email address">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-sm text-gray-500">(leave blank to keep current)</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               value=""
                               class="w-full border border-gray-300 rounded-md px-3 py-2 pr-20 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Enter new password">
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <button type="button" onclick="togglePasswordVisibility(this)" 
                                    class="px-3 py-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" onclick="generatePasswordForField()" 
                                    class="px-3 py-2 text-indigo-600 hover:text-indigo-500" title="Generate Password">
                                <i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Website URL -->
                <div>
                    <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">
                        Website URL
                    </label>
                    <input type="url" name="website_url" id="website_url"
                           value="{{ old('website_url', $credential['website_url']) }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="https://example.com">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Additional notes or information">{{ old('notes', $credential['notes']) }}</textarea>
                </div>

                <!-- Is Favorite -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_favorite" id="is_favorite" value="1"
                               {{ old('is_favorite', $credential['is_favorite']) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_favorite" class="ml-2 block text-sm text-gray-900">
                            Mark as favorite
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('credentials.show', $credential['id']) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Credential
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function generatePasswordForField() {
    const passwordField = document.getElementById('password');
    const newPassword = generatePassword(16);
    passwordField.value = newPassword;
    showNotification('Password generated successfully!');
}

function generatePassword(length = 16) {
    const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
    let password = '';
    
    // Ensure at least one character from each type
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numbers = '0123456789';
    const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
    
    password += lowercase[Math.floor(Math.random() * lowercase.length)];
    password += uppercase[Math.floor(Math.random() * uppercase.length)];
    password += numbers[Math.floor(Math.random() * numbers.length)];
    password += symbols[Math.floor(Math.random() * symbols.length)];
    
    // Fill the rest randomly
    for (let i = 4; i < length; i++) {
        password += charset[Math.floor(Math.random() * charset.length)];
    }
    
    // Shuffle the password
    return password.split('').sort(() => Math.random() - 0.5).join('');
}
</script>
@endpush
@endsection 