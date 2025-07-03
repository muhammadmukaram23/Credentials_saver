@extends('layouts.app')

@section('title', 'Add Credit Card - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <a href="{{ route('credit-cards.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-plus text-green-600 mr-2"></i>
                        Add New Credit Card
                    </h1>
                    <p class="text-gray-600">Securely store your credit card information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <form action="{{ route('credit-cards.store') }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Name -->
                <div class="md:col-span-2">
                    <label for="card_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Card Name *
                    </label>
                    <input type="text" name="card_name" id="card_name" required
                           value="{{ old('card_name') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g., Personal Visa, Business Mastercard">
                </div>

                <!-- Cardholder Name -->
                <div>
                    <label for="cardholder_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Cardholder Name *
                    </label>
                    <input type="text" name="cardholder_name" id="cardholder_name" required
                           value="{{ old('cardholder_name') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="Full name as on card">
                </div>

                <!-- Card Type -->
                <div>
                    <label for="card_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Card Type *
                    </label>
                    <select name="card_type" id="card_type" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="">Select card type</option>
                        <option value="visa" {{ old('card_type') == 'visa' ? 'selected' : '' }}>Visa</option>
                        <option value="mastercard" {{ old('card_type') == 'mastercard' ? 'selected' : '' }}>Mastercard</option>
                        <option value="amex" {{ old('card_type') == 'amex' ? 'selected' : '' }}>American Express</option>
                        <option value="discover" {{ old('card_type') == 'discover' ? 'selected' : '' }}>Discover</option>
                        <option value="other" {{ old('card_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Card Number -->
                <div>
                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Card Number *
                    </label>
                    <input type="text" name="card_number" id="card_number" required
                           value="{{ old('card_number') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="1234 5678 9012 3456"
                           maxlength="19"
                           oninput="formatCardNumber(this)">
                </div>

                <!-- Bank Name -->
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name
                    </label>
                    <input type="text" name="bank_name" id="bank_name"
                           value="{{ old('bank_name') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="e.g., Chase, Bank of America">
                </div>

                <!-- Expiry Month -->
                <div>
                    <label for="expiry_month" class="block text-sm font-medium text-gray-700 mb-2">
                        Expiry Month *
                    </label>
                    <select name="expiry_month" id="expiry_month" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="">Select month</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('expiry_month') == $i ? 'selected' : '' }}>
                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }} - {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Expiry Year -->
                <div>
                    <label for="expiry_year" class="block text-sm font-medium text-gray-700 mb-2">
                        Expiry Year *
                    </label>
                    <select name="expiry_year" id="expiry_year" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <option value="">Select year</option>
                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                            <option value="{{ $i }}" {{ old('expiry_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- CVV -->
                <div>
                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">
                        CVV *
                    </label>
                    <input type="password" name="cvv" id="cvv" required
                           value="{{ old('cvv') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                           placeholder="123"
                           maxlength="4">
                </div>

                <!-- Billing Address -->
                <div class="md:col-span-2">
                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Billing Address
                    </label>
                    <textarea name="billing_address" id="billing_address" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                              placeholder="Enter billing address">{{ old('billing_address') }}</textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-green-500 focus:border-green-500"
                              placeholder="Additional notes or information">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('credit-cards.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Credit Card
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = value;
}
</script>
@endpush
@endsection 