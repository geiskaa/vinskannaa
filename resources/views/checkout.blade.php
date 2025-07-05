@extends('layouts.app')

@section('title', 'Checkout - ThriftHub')

@section('head')
    <!-- Midtrans Snap JS -->
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <span>Keranjang</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 font-medium">Checkout</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-400">Pembayaran</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Address Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat Pengiriman
                        </h2>
                        @if ($addresses->count() > 1)
                            <button type="button" id="change-address-btn"
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Ubah Alamat
                            </button>
                        @endif
                    </div>

                    <form id="checkout-form" action="/checkout/token" method="POST">
                        @csrf

                        <!-- Current Selected Address Display -->
                        <div id="selected-address-display" class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            @if ($primaryAddress)
                                <input type="hidden" name="address_id" value="{{ $primaryAddress->id }}"
                                    id="selected-address-id">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $primaryAddress->recipient_name }}
                                            </h3>
                                            @if ($primaryAddress->label)
                                                <span
                                                    class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                    {{ $primaryAddress->label }}
                                                </span>
                                            @endif
                                            @if ($primaryAddress->is_primary)
                                                <span
                                                    class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                    Utama
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 mb-1">{{ $primaryAddress->phone }}</p>
                                        <p class="text-gray-600">{{ $primaryAddress->formatted_address }}</p>
                                    </div>

                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 mb-2">Belum ada alamat tersimpan</p>
                                    <button type="button" class="text-blue-600 hover:text-blue-800 font-medium"
                                        onclick="window.location.href='{{ route('profile.edit') }}'">
                                        Tambah Alamat Baru
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Address Selection Modal/Dropdown -->
                        @if ($addresses->count() > 1)
                            <div id="address-selection" class="hidden mt-4">
                                <h4 class="font-medium text-gray-900 mb-3">Pilih Alamat Pengiriman:</h4>
                                <div class="space-y-3 max-h-60 overflow-y-auto">
                                    @foreach ($addresses as $address)
                                        <label
                                            class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors address-option">
                                            <input type="radio" name="address_selection" value="{{ $address->id }}"
                                                class="mt-1 w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                {{ $primaryAddress && $primaryAddress->id == $address->id ? 'checked' : '' }}>
                                            <div class="ml-3 flex-1">
                                                <div class="flex items-center mb-1">
                                                    <span
                                                        class="font-medium text-gray-900">{{ $address->recipient_name }}</span>
                                                    @if ($address->label)
                                                        <span
                                                            class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                            {{ $address->label }}
                                                        </span>
                                                    @endif
                                                    @if ($address->is_primary)
                                                        <span
                                                            class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">
                                                            Utama
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 mb-1">{{ $address->phone }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->formatted_address }}</p>
                                            </div>
                                            <button type="button"
                                                onclick="window.location.href='{{ route('profile.edit') }}'"
                                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                Edit
                                            </button>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <button type="button" id="confirm-address-btn"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Konfirmasi Alamat
                                    </button>
                                    <button type="button" id="cancel-address-btn"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        @endif

                    </form>
                </div>

                <!-- Shipping Method Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Metode Pengiriman
                    </h2>

                    <div class="space-y-3">
                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="shipping_method" value="regular" form="checkout-form"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">Reguler (5-7 hari)</p>
                                        <p class="text-sm text-gray-600">Pengiriman standar</p>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">Rp 15.000</span>
                                </div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="shipping_method" value="express" form="checkout-form"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">Express (2-3 hari)</p>
                                        <p class="text-sm text-gray-600">Pengiriman cepat</p>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">Rp 25.000</span>
                                </div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="radio" name="shipping_method" value="same_day" form="checkout-form"
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">Same Day (Hari ini)</p>
                                        <p class="text-sm text-gray-600">Khusus area Surabaya</p>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">Rp 35.000</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Catatan Pesanan (Opsional)</h2>
                    <textarea name="notes" rows="3" form="checkout-form"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tambahkan catatan untuk pesanan Anda...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @forelse($cartItems as $item)
                            <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                                <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://via.placeholder.com/60x60?text=No+Image' }}"
                                    alt="{{ $item->product->name }}" class="w-12 h-12 rounded-lg object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l-2.5 5m0 0h5.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6">
                                    </path>
                                </svg>
                                <p class="text-gray-500">Keranjang kosong</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
                            <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ongkos kirim</span>
                            <span class="text-gray-900" id="shipping-cost">Rp 15.000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pajak (11%)</span>
                            <span class="text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-3">
                        <div class="flex justify-between text-lg font-semibold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-blue-600" id="total-amount">Rp
                                {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button type="submit" form="checkout-form"
                        class="cursor-pointer w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="checkout-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>Lanjut ke Pembayaran</span>
                    </button>

                    <!-- Security Notice -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-900">Pembayaran Aman</p>
                                <p class="text-xs text-gray-600">Diproses oleh Midtrans dengan enkripsi SSL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        window.checkoutData = {
            subtotal: {{ $subtotal }},
            tax: {{ $tax }},
            addresses: {!! json_encode($addresses) !!}
        };
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection
