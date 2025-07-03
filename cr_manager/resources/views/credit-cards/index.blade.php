@extends('layouts.app')

@section('title', 'Credit Cards - Password Manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-credit-card text-green-600 mr-2"></i>
                        Credit Cards
                    </h1>
                    <p class="text-gray-600">Manage your credit card information securely</p>
                </div>
                <a href="{{ route('credit-cards.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Credit Card
                </a>
            </div>
        </div>
    </div>

    <!-- Credit Cards List -->
    @if(count($creditCards) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($creditCards as $card)
                <div class="bg-white overflow-hidden shadow rounded-lg card-hover transition-all">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $card['card_name'] ?? 'Unnamed Card' }}</h3>
                                @if(isset($card['cardholder_name']) && $card['cardholder_name'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $card['cardholder_name'] }}
                                    </p>
                                @endif
                                @if(isset($card['card_number']) && $card['card_number'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-credit-card mr-1"></i>
                                        •••• •••• •••• {{ substr($card['card_number'], -4) }}
                                    </p>
                                @endif
                                @if(isset($card['expiry_month']) && isset($card['expiry_year']))
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ str_pad($card['expiry_month'], 2, '0', STR_PAD_LEFT) }}/{{ $card['expiry_year'] }}
                                    </p>
                                @endif
                                @if(isset($card['bank_name']) && $card['bank_name'])
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $card['bank_name'] }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-clock mr-1"></i>
                                    Updated {{ date('M j, Y', strtotime($card['updated_at'])) }}
                                </p>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex space-x-2">
                                    <a href="{{ route('credit-cards.show', $card['id']) }}" 
                                       class="text-green-600 hover:text-green-500" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('credit-cards.edit', $card['id']) }}" 
                                       class="text-yellow-600 hover:text-yellow-500" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('credit-cards.destroy', $card['id']) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmDelete('Are you sure you want to delete this credit card?')" 
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
                <i class="fas fa-credit-card text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No credit cards found</h3>
                <p class="text-gray-600 mb-6">Get started by adding your first credit card.</p>
                <a href="{{ route('credit-cards.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Add Your First Credit Card
                </a>
            </div>
        </div>
    @endif
</div>
@endsection 