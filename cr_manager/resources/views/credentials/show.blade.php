@extends('layouts.app')

@section('title', 'View Credential - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('credentials.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                            <i class="fas fa-key text-indigo-600 mr-2"></i>
                            {{ $credential['service_name'] }}
                            @if($credential['is_favorite'])
                                <i class="fas fa-heart text-red-500 ml-2"></i>
                            @endif
                        </h1>
                        <p class="text-gray-600">Credential details</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('credentials.edit', $credential['id']) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <form action="{{ route('credentials.destroy', $credential['id']) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirmDelete('Are you sure you want to delete this credential?')" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Credential Details -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Name -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Service Name</dt>
                    <dd class="text-lg text-gray-900">{{ $credential['service_name'] }}</dd>
                </div>

                <!-- Category -->
                @if(isset($credential['category']))
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Category</dt>
                    <dd class="text-lg text-gray-900">{{ $credential['category']['name'] ?? 'N/A' }}</dd>
                </div>
                @endif

                <!-- Username -->
                @if($credential['username'])
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Username</dt>
                    <dd class="text-lg text-gray-900 flex items-center">
                        {{ $credential['username'] }}
                        <button onclick="copyToClipboard('{{ $credential['username'] }}')" 
                                class="ml-2 text-gray-400 hover:text-gray-600" title="Copy Username">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dd>
                </div>
                @endif

                <!-- Email -->
                @if($credential['email'])
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                    <dd class="text-lg text-gray-900 flex items-center">
                        {{ $credential['email'] }}
                        <button onclick="copyToClipboard('{{ $credential['email'] }}')" 
                                class="ml-2 text-gray-400 hover:text-gray-600" title="Copy Email">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dd>
                </div>
                @endif

                <!-- Password -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Password</dt>
                    <dd class="text-lg text-gray-900 flex items-center">
                        <span id="password-field" class="font-mono">••••••••••••</span>
                        <button onclick="togglePasswordDisplay()" 
                                class="ml-2 text-gray-400 hover:text-gray-600" title="Show/Hide Password">
                            <i id="password-toggle-icon" class="fas fa-eye"></i>
                        </button>
                        @if(isset($credential['password']) && $credential['password'])
                            <button onclick="copyToClipboard('{{ $credential['password'] }}')" 
                                    class="ml-2 text-gray-400 hover:text-gray-600" title="Copy Password">
                                <i class="fas fa-copy"></i>
                            </button>
                        @endif
                    </dd>
                </div>

                <!-- Website URL -->
                @if($credential['website_url'])
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Website URL</dt>
                    <dd class="text-lg text-gray-900 flex items-center">
                        <a href="{{ $credential['website_url'] }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">
                            {{ $credential['website_url'] }}
                        </a>
                        <button onclick="copyToClipboard('{{ $credential['website_url'] }}')" 
                                class="ml-2 text-gray-400 hover:text-gray-600" title="Copy URL">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dd>
                </div>
                @endif

                <!-- Notes -->
                @if($credential['notes'])
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Notes</dt>
                    <dd class="text-lg text-gray-900 whitespace-pre-wrap">{{ $credential['notes'] }}</dd>
                </div>
                @endif

                <!-- Timestamps -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Created</dt>
                    <dd class="text-lg text-gray-900">{{ date('M j, Y g:i A', strtotime($credential['created_at'])) }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Last Updated</dt>
                    <dd class="text-lg text-gray-900">{{ date('M j, Y g:i A', strtotime($credential['updated_at'])) }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>

@push('scripts')
<script>
let passwordVisible = false;

function togglePasswordDisplay() {
    const passwordField = document.getElementById('password-field');
    const toggleIcon = document.getElementById('password-toggle-icon');
    
    if (passwordVisible) {
        passwordField.textContent = '••••••••••••';
        toggleIcon.className = 'fas fa-eye';
        passwordVisible = false;
    } else {
        passwordField.textContent = '{{ $credential['password'] ?? '' }}';
        toggleIcon.className = 'fas fa-eye-slash';
        passwordVisible = true;
    }
}
</script>
@endpush
@endsection 