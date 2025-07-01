@extends('layouts.app')

@section('title', 'Keranjang Belanja - ThriftHub')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                            Keranjang Belanja
                        </h1>
                        <p class="text-gray-600 mt-1">Kelola produk favorit Anda</p>
                    </div>
                </div>
                @if ($cartItems->count() > 0)
                    <button onclick="clearCart()"
                        class="group flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl hover:from-red-600 hover:to-pink-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <span class="font-medium">Kosongkan Keranjang</span>
                    </button>
                @endif
            </div>

            @if ($cartItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-6">
                        @foreach ($cartItems as $item)
                            <div class="group bg-white/70 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50 p-6 cart-item hover:shadow-2xl transition-all duration-500 hover:scale-[1.02] hover:bg-white/80"
                                data-cart-id="{{ $item->id }}">
                                <div class="flex items-center space-x-6">
                                    <!-- Product Image -->
                                    <div
                                        class="relative w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl overflow-hidden flex-shrink-0 shadow-inner">
                                        @if (
                                            $item->product->image &&
                                                (Str::startsWith($item->product->image, 'http') || Storage::disk('public')->exists($item->product->image)))
                                            <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @elseif ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                            @php
                                                $firstImage = $item->product->images[0];
                                                $isExternal = Str::startsWith($firstImage, 'http');
                                                $imageExists =
                                                    !$isExternal && Storage::disk('public')->exists($firstImage);
                                            @endphp
                                            @if ($isExternal || $imageExists)
                                                <img src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                                    <svg class="w-10 h-10 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0 space-y-2">
                                        <h3
                                            class="text-xl font-bold text-gray-900 truncate group-hover:text-blue-600 transition-colors duration-300">
                                            {{ $item->product->name }}
                                        </h3>
                                        @if ($item->product->category)
                                            <div
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 border border-blue-200">
                                                {{ Str::title($item->product->category) }}
                                            </div>
                                        @endif
                                        <div class="flex items-center space-x-4">
                                            <p
                                                class="text-2xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </p>
                                            @if ($item->product->stock !== null)
                                                <div class="flex items-center space-x-1">
                                                    <div
                                                        class="w-2 h-2 rounded-full {{ $item->product->stock > 0 ? 'bg-green-400' : 'bg-red-400' }}">
                                                    </div>
                                                    <p
                                                        class="text-xs {{ $item->product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                        Stok:
                                                        {{ $item->product->stock > 0 ? $item->product->stock : 'Habis' }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="flex items-center bg-gradient-to-r from-gray-50 to-white border-2 border-gray-200 rounded-2xl shadow-inner overflow-hidden">
                                            <button
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="px-4 py-3 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-pink-600 transition-all duration-300 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <div
                                                class="px-6 py-3 bg-gradient-to-r from-blue-50 to-purple-50 border-x-2 border-gray-200">
                                                <span
                                                    class="text-lg font-bold text-gray-800 quantity-display">{{ $item->quantity }}</span>
                                            </div>
                                            <button
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="px-4 py-3 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-blue-600 transition-all duration-300"
                                                {{ $item->product->stock !== null && $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Remove Button -->
                                        <button onclick="removeItem({{ $item->id }})"
                                            class="group p-3 bg-gradient-to-r from-red-100 to-pink-100 text-red-600 hover:from-red-500 hover:to-pink-600 hover:text-white rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-110">
                                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-medium">Subtotal</span>
                                        <span
                                            class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent item-subtotal">
                                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div
                            class="sticky top-4 bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 p-8 space-y-6">
                            <div class="text-center">
                                <h2
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-2">
                                    Ringkasan Pesanan
                                </h2>
                                <div class="w-20 h-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mx-auto">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div
                                    class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        <span class="text-gray-700 font-medium">Total Item</span>
                                    </div>
                                    <span class="font-bold text-gray-900 total-items">{{ $cartItems->sum('quantity') }}
                                        item</span>
                                </div>

                                <div
                                    class="flex justify-between items-center p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-gray-700 font-medium">Subtotal</span>
                                    </div>
                                    <span class="font-bold text-gray-900 total-price">
                                        Rp
                                        {{ number_format($cartItems->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t-2 border-dashed border-gray-300 pt-6">
                                <div
                                    class="flex justify-between items-center p-6 bg-gradient-to-r from-purple-100 to-blue-100 rounded-2xl">
                                    <span class="text-xl font-bold text-gray-900">Total Pembayaran</span>
                                    <span
                                        class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent total-price">
                                        Rp
                                        {{ number_format($cartItems->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}
                                    </span>
                                </div>
                            </div>

                            <button
                                class="group w-full bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white py-4 px-6 rounded-2xl hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 transition-all duration-300 font-bold text-lg shadow-2xl hover:shadow-purple-500/25 transform hover:scale-105 relative overflow-hidden">
                                <span class="relative z-10 flex items-center justify-center space-x-2">
                                    <svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                    <span>Lanjut ke Checkout</span>
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                                </div>
                            </button>

                            <!-- Trust Badges -->
                            <div class="grid grid-cols-3 gap-3 pt-4">
                                <div class="text-center p-3 bg-gradient-to-b from-green-50 to-green-100 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600 mx-auto mb-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-xs text-green-700 font-medium">Aman</p>
                                </div>
                                <div class="text-center p-3 bg-gradient-to-b from-blue-50 to-blue-100 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600 mx-auto mb-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <p class="text-xs text-blue-700 font-medium">Cepat</p>
                                </div>
                                <div class="text-center p-3 bg-gradient-to-b from-purple-50 to-purple-100 rounded-xl">
                                    <svg class="w-6 h-6 text-purple-600 mx-auto mb-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <p class="text-xs text-purple-700 font-medium">Terpercaya</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="relative mb-8">
                            <div
                                class="w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full mx-auto flex items-center justify-center shadow-2xl">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                                    </path>
                                </svg>
                            </div>
                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-r from-red-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-sm">0</span>
                            </div>
                        </div>

                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Keranjang Masih Kosong</h3>
                        <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                            Waktunya berburu produk thrift favorit Anda! <br>
                            Temukan barang-barang unik dengan harga terjangkau.
                        </p>

                        <a href="{{ route('dashboard') }}"
                            class="group inline-flex items-center space-x-3 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 text-white px-8 py-4 rounded-2xl hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 transition-all duration-300 font-bold text-lg shadow-2xl hover:shadow-purple-500/25 transform hover:scale-105 relative overflow-hidden">
                            <svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>Mulai Belanja Sekarang</span>
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3"></div>

    <script>
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) return;

            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const quantityDisplay = cartItem.querySelector('.quantity-display');
            const subtotalDisplay = cartItem.querySelector('.item-subtotal');

            // Add loading state
            cartItem.style.opacity = '0.7';
            cartItem.style.pointerEvents = 'none';

            fetch(`/cart/${cartId}/quantity`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update quantity display with animation
                        quantityDisplay.style.transform = 'scale(1.2)';
                        quantityDisplay.textContent = newQuantity;
                        setTimeout(() => {
                            quantityDisplay.style.transform = 'scale(1)';
                        }, 200);

                        // Update subtotal
                        const price = parseFloat(cartItem.querySelector('p').textContent.replace(/[^\d]/g, ''));
                        const newSubtotal = newQuantity * price;
                        subtotalDisplay.textContent = `Rp ${newSubtotal.toLocaleString('id-ID')}`;

                        // Update totals
                        updateTotals();

                        // Update cart counter in navbar
                        updateCartCounter(data.cart_count);

                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan', 'error');
                })
                .finally(() => {
                    // Remove loading state
                    cartItem.style.opacity = '1';
                    cartItem.style.pointerEvents = 'auto';
                });
        }

        function removeItem(cartId) {
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);

            if (confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
                fetch(`/cart/${cartId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Enhanced removal animation
                            cartItem.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                            cartItem.style.opacity = '0';
                            cartItem.style.transform = 'translateX(-100%) scale(0.8)';
                            cartItem.style.maxHeight = '0';
                            cartItem.style.marginBottom = '0';
                            cartItem.style.paddingTop = '0';
                            cartItem.style.paddingBottom = '0';

                            setTimeout(() => {
                                cartItem.remove();
                                updateTotals();

                                // Check if cart is now empty
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    location.reload();
                                }
                            }, 500);

                            // Update cart counter in navbar
                            updateCartCounter(data.cart_count);

                            showToast(data.message, 'success');
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function clearCart() {
            if (confirm('Yakin ingin mengosongkan seluruh keranjang?')) {
                fetch('/cart', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add fade out animation before reload
                            document.body.style.transition = 'opacity 0.3s ease';
                            document.body.style.opacity = '0';
                            setTimeout(() => {
                                location.reload();
                            }, 300);
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function updateTotals() {
            const cartItems = document.querySelectorAll('.cart-item');
            let totalItems = 0;
            let totalPrice = 0;

            cartItems.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-display').textContent);
                const price = parseFloat(item.querySelector('.item-subtotal').textContent.replace(/[^\d]/g, ''));

                totalItems += quantity;
                totalPrice += price;
            });

            // Update displays with animation
            const totalItemsEl = document.querySelector('.total-items');
            const totalPriceEls = document.querySelectorAll('.total-price');

            totalItemsEl.style.transform = 'scale(1.1)';
            totalItemsEl.textContent = `${totalItems} item`;
            setTimeout(() => {
                totalItemsEl.style.transform = 'scale(1)';
            }, 200);

            totalPriceEls.forEach(el => {
                el.style.transform = 'scale(1.1)';
                el.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
                setTimeout(() => {
                    el.style.transform = 'scale(1)';
                }, 200);
            });
        }

        function updateCartCounter(count) {
            const cartCounter = document.getElementById('cart-counter');
            if (cartCounter) {
                cartCounter.style.transform = 'scale(1.3)';
                cartCounter.textContent = count;
                if (count > 0) {
                    cartCounter.classList.remove('hidden');
                } else {
                    cartCounter.classList.add('hidden');
                }
                setTimeout(() => {
                    cartCounter.style.transform = 'scale(1)';
                }, 200);
            }
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');

            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-500 ease-out translate-x-full opacity-0`;

            const bgGradient = type === 'success' ?
                'bg-gradient-to-r from-green-500 to-emerald-600' :
                'bg-gradient-to-r from-red-500 to-pink-600';

            const icon = type === 'success' ?
                `<div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>` :
                `<div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>`;

            toast.innerHTML = `
                <div class="${bgGradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-4 min-w-80 max-w-md backdrop-blur-sm border border-white/20">
                    ${icon}
                    <div class="flex-1">
                        <p class="font-semibold text-sm">${type === 'success' ? 'Berhasil!' : 'Oops!'}</p>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                    <button onclick="removeToast(this)" class="flex-shrink-0 ml-2 text-white/80 hover:text-white transition-colors p-1 hover:bg-white/20 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Trigger entrance animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toast);
            }, 5000);
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
                }, 500);
            }
        }

        // Add smooth scroll behavior and entrance animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animation to cart items
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add floating animation to summary card
            const summaryCard = document.querySelector('.sticky');
            if (summaryCard) {
                summaryCard.style.animation = 'float 6s ease-in-out infinite';
            }

            // Add pulse animation to checkout button
            const checkoutBtn = summaryCard?.querySelector('button');
            if (checkoutBtn) {
                setInterval(() => {
                    checkoutBtn.style.animation = 'pulse 2s ease-in-out';
                    setTimeout(() => {
                        checkoutBtn.style.animation = '';
                    }, 2000);
                }, 10000);
            }
        });

        // Add custom CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-5px); }
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.02); }
            }
            
            .cart-item {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .cart-item:hover {
                transform: translateY(-2px);
            }
            
            /* Smooth transitions for all interactive elements */
            button, .quantity-display, .total-items, .total-price {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Loading shimmer effect */
            .loading {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: shimmer 1.5s infinite;
            }
            
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
