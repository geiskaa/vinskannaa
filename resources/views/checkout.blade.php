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
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Alamat Pengiriman
                    </h2>

                    <form id="checkout-form" action="/checkout/token" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Lengkap</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                    Telepon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                                Lengkap</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Jalan, RT/RW, Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                <input type="text" id="province" name="province" value="{{ old('province') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Kode
                                    Pos</label>
                                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                            </div>
                        </div>
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

                <!-- Payment Info Section -->

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
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
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
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
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
        document.addEventListener('DOMContentLoaded', function() {
            const shippingInputs = document.querySelectorAll('input[name="shipping_method"]');
            const shippingCostEl = document.getElementById('shipping-cost');
            const totalAmountEl = document.getElementById('total-amount');
            const checkoutBtn = document.getElementById('checkout-btn');
            const checkoutForm = document.getElementById('checkout-form');

            const subtotal = {{ $subtotal }};
            const tax = {{ $tax }};

            const shippingCosts = {
                'regular': 15000,
                'express': 25000,
                'same_day': 35000
            };

            // Update shipping cost when method changes
            shippingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const selectedShipping = this.value;
                    const shippingCost = shippingCosts[selectedShipping];
                    const newTotal = subtotal + tax + shippingCost;

                    shippingCostEl.textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
                    totalAmountEl.textContent = 'Rp ' + newTotal.toLocaleString('id-ID');
                });
            });

            // Form submission
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable button and show loading
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                <span>Memproses...</span>
            </div>
        `;

                // Get form data
                const formData = new FormData(this);

                // Add selected shipping cost to form data
                const selectedShippingInput = document.querySelector(
                    'input[name="shipping_method"]:checked');
                if (!selectedShippingInput) {
                    showToast('Silakan pilih metode pengiriman', 'error');
                    resetButton();
                    return;
                }

                const selectedShipping = selectedShippingInput.value;
                const shippingCost = shippingCosts[selectedShipping];
                formData.append('shipping_cost', shippingCost);

                // Calculate total
                const total = subtotal + tax + shippingCost;
                formData.append('total_amount', total);

                // Submit form dengan proper error handling
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Response data:", data);

                        if (data.success && data.snap_token) {
                            showToast('Mengarahkan ke halaman pembayaran...', 'success');

                            // Use Midtrans Snap
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    console.log('Payment success:', result);
                                    showToast('Pembayaran berhasil!', 'success');
                                    setTimeout(() => {
                                        window.location.href = '/orders/' + data
                                            .order_id;
                                    }, 1500);
                                },
                                onPending: function(result) {
                                    console.log('Payment pending:', result);
                                    showToast(
                                        'Pembayaran pending, silakan selesaikan pembayaran.',
                                        'warning');
                                    setTimeout(() => {
                                        window.location.href = '/orders/' + data
                                            .order_id;
                                    }, 1500);
                                },
                                onError: function(result) {
                                    console.log('Payment error:', result);
                                    showToast('Pembayaran gagal, silakan coba lagi.',
                                        'error');
                                    resetButton();
                                },
                                onClose: function() {
                                    console.log('Payment popup closed');
                                    showToast('Pembayaran dibatalkan.', 'warning');
                                    resetButton();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Tidak ada snap token yang diterima');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'Terjadi kesalahan saat memproses pesanan';

                        if (error.message) {
                            errorMessage = error.message;
                        } else if (error.errors) {
                            // Validation errors
                            const firstError = Object.values(error.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        }

                        showToast(errorMessage, 'error');
                        resetButton();
                    });
            });

            function resetButton() {
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Lanjut ke Pembayaran</span>
        `;
            }

            function showToast(message, type = 'success') {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

                let bgColor, icon;
                switch (type) {
                    case 'success':
                        bgColor = 'bg-green-500';
                        icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
                        break;
                    case 'warning':
                        bgColor = 'bg-yellow-500';
                        icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>`;
                        break;
                    default:
                        bgColor = 'bg-red-500';
                        icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
                }

                toast.innerHTML = `
            <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-64 max-w-sm">
                <div class="flex-shrink-0">${icon}</div>
                <div class="flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <button onclick="removeToast(this)" class="flex-shrink-0 ml-2 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                setTimeout(() => {
                    removeToast(toast);
                }, 4000);
            }

            function removeToast(element) {
                const toast = element.closest('.transform');
                if (toast) {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }
            }

            // Make removeToast globally accessible
            window.removeToast = removeToast;
        });
    </script>
@endsection
