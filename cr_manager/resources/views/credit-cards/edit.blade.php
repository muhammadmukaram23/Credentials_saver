@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Credit Card</h1>
            <a href="{{ route('credit-cards.show', $creditCard['id'] ?? '') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>

        <form id="creditCardForm" method="POST" action="{{ route('credit-cards.update', $creditCard['id'] ?? '') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Card Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="card_name" 
                               name="card_name" 
                               value="{{ $creditCard['card_name'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>

                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Bank Name
                        </label>
                        <input type="text" 
                               id="bank_name" 
                               name="bank_name" 
                               value="{{ $creditCard['bank_name'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Card Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="card_number" 
                               name="card_number" 
                               value="{{ $creditCard['card_number'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="1234 5678 9012 3456"
                               maxlength="19"
                               required>
                    </div>

                    <div>
                        <label for="cardholder_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Cardholder Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="cardholder_name" 
                               name="cardholder_name" 
                               value="{{ $creditCard['cardholder_name'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expiry_month" class="block text-sm font-medium text-gray-700 mb-2">
                                Expiry Month <span class="text-red-500">*</span>
                            </label>
                            <select id="expiry_month" 
                                    name="expiry_month" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required>
                                <option value="">Month</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ (isset($creditCard['expiry_month']) && $creditCard['expiry_month'] == $i) ? 'selected' : '' }}>
                                        {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="expiry_year" class="block text-sm font-medium text-gray-700 mb-2">
                                Expiry Year <span class="text-red-500">*</span>
                            </label>
                            <select id="expiry_year" 
                                    name="expiry_year" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required>
                                <option value="">Year</option>
                                @for($i = date('Y'); $i <= date('Y') + 20; $i++)
                                    <option value="{{ $i }}" {{ (isset($creditCard['expiry_year']) && $creditCard['expiry_year'] == $i) ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">
                            CVV <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="cvv" 
                               name="cvv" 
                               value="{{ $creditCard['cvv'] ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               maxlength="4"
                               required>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label for="card_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Card Type
                        </label>
                        <select id="card_type" 
                                name="card_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Card Type</option>
                            <option value="visa" {{ (isset($creditCard['card_type']) && $creditCard['card_type'] == 'visa') ? 'selected' : '' }}>Visa</option>
                            <option value="mastercard" {{ (isset($creditCard['card_type']) && $creditCard['card_type'] == 'mastercard') ? 'selected' : '' }}>MasterCard</option>
                            <option value="amex" {{ (isset($creditCard['card_type']) && $creditCard['card_type'] == 'amex') ? 'selected' : '' }}>American Express</option>
                            <option value="discover" {{ (isset($creditCard['card_type']) && $creditCard['card_type'] == 'discover') ? 'selected' : '' }}>Discover</option>
                            <option value="other" {{ (isset($creditCard['card_type']) && $creditCard['card_type'] == 'other') ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

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
                                    <option value="{{ $category['id'] }}" {{ (isset($creditCard['category_id']) && $creditCard['category_id'] == $category['id']) ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Billing Address
                        </label>
                        <textarea id="billing_address" 
                                  name="billing_address" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Enter billing address">{{ $creditCard['billing_address'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Additional notes">{{ $creditCard['notes'] ?? '' }}</textarea>
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
                    <i class="fas fa-save mr-2"></i>Update Credit Card
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Format card number input
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// CVV input validation
document.getElementById('cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/gi, '');
});

// Form will submit normally to Laravel route
</script>
@endsection 