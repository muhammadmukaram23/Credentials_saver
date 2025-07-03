@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Credit Card Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('credit-cards.edit', $creditCard['id'] ?? '') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('credit-cards.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card Information -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $creditCard['bank_name'] ?? 'Unknown Bank' }}</h3>
                        <p class="text-blue-100">{{ $creditCard['card_type'] ?? 'Credit Card' }}</p>
                    </div>
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-blue-100 mb-1">Card Number</p>
                    <div class="flex items-center space-x-2">
                        <span id="cardNumber" class="text-lg font-mono tracking-wider">
                            {{ isset($creditCard['card_number']) ? str_repeat('*', strlen($creditCard['card_number']) - 4) . substr($creditCard['card_number'], -4) : '**** **** **** ****' }}
                        </span>
                        <button onclick="toggleCardNumber()" class="text-blue-200 hover:text-white">
                            <i class="fas fa-eye" id="cardToggleIcon"></i>
                        </button>
                        <button onclick="copyToClipboard('{{ $creditCard['card_number'] ?? '' }}')" class="text-blue-200 hover:text-white">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-blue-100 mb-1">Cardholder</p>
                        <p class="font-semibold">{{ $creditCard['cardholder_name'] ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-blue-100 mb-1">Expires</p>
                        <p class="font-semibold">{{ isset($creditCard['expiry_month']) && isset($creditCard['expiry_year']) ? sprintf('%02d/%s', $creditCard['expiry_month'], $creditCard['expiry_year']) : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                    <div class="flex items-center space-x-2">
                        <span id="cvv" class="text-lg font-mono">***</span>
                        <button onclick="toggleCVV()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-eye" id="cvvToggleIcon"></i>
                        </button>
                        <button onclick="copyToClipboard('{{ $creditCard['cvv'] ?? '' }}')" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                @if(isset($creditCard['billing_address']) && $creditCard['billing_address'])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                    <p class="text-gray-900 whitespace-pre-line">{{ $creditCard['billing_address'] }}</p>
                </div>
                @endif

                @if(isset($creditCard['notes']) && $creditCard['notes'])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <p class="text-gray-900 whitespace-pre-line">{{ $creditCard['notes'] }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        {{ $creditCard['category']['name'] ?? 'Uncategorized' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <strong>Created:</strong> {{ isset($creditCard['created_at']) ? date('M j, Y g:i A', strtotime($creditCard['created_at'])) : 'N/A' }}
                </div>
                <div>
                    <strong>Last Modified:</strong> {{ isset($creditCard['updated_at']) ? date('M j, Y g:i A', strtotime($creditCard['updated_at'])) : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const cardNumber = '{{ $creditCard['card_number'] ?? '' }}';
const cvvValue = '{{ $creditCard['cvv'] ?? '' }}';
let cardNumberVisible = false;
let cvvVisible = false;

function toggleCardNumber() {
    const element = document.getElementById('cardNumber');
    const icon = document.getElementById('cardToggleIcon');
    
    if (cardNumberVisible) {
        element.textContent = cardNumber.replace(/(.{4})/g, '$1 ').trim().replace(/\d(?=\d{4})/g, '*');
        icon.className = 'fas fa-eye';
    } else {
        element.textContent = cardNumber.replace(/(.{4})/g, '$1 ').trim();
        icon.className = 'fas fa-eye-slash';
    }
    cardNumberVisible = !cardNumberVisible;
}

function toggleCVV() {
    const element = document.getElementById('cvv');
    const icon = document.getElementById('cvvToggleIcon');
    
    if (cvvVisible) {
        element.textContent = '***';
        icon.className = 'fas fa-eye';
    } else {
        element.textContent = cvvValue;
        icon.className = 'fas fa-eye-slash';
    }
    cvvVisible = !cvvVisible;
}

function copyToClipboard(text) {
    if (!text) return;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection 