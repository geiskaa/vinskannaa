@extends('layouts.app')

@section('title', 'Keranjang Belanja - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h1>
            @if ($cartItems->count() > 0)
                <button onclick="clearCart()" class="text-red-600 hover:text-red-800 text-sm font-medium">
                    Kosongkan Keranjang
                </button>
            @endif
        </div>

        @if ($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cartItems as $item)
                        <div class="bg-white rounded-lg shadow-sm border p-4 cart-item" data-cart-id="{{ $item->id }}">
                            <div class="flex items-center space-x-4">
                                <!-- Product Image -->
                                <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if (
                                        $item->product->image &&
                                            (Str::startsWith($item->product->image, 'http') || Storage::disk('public')->exists($item->product->image)))
                                        <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @elseif ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                        @php
                                            $firstImage = $item->product->images[0];
                                            $isExternal = Str::startsWith($firstImage, 'http');
                                            $imageExists = !$isExternal && Storage::disk('public')->exists($firstImage);
                                        @endphp
                                        @if ($isExternal || $imageExists)
                                            <img src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $item->product->name }}</h3>
                                    @if ($item->product->category)
                                        <p class="text-sm text-gray-500">{{ Str::title($item->product->category) }}</p>
                                    @endif
                                    <p class="text-lg font-bold text-gray-900 mt-1">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                    @if ($item->product->stock !== null)
                                        <p class="text-xs text-gray-500">
                                            Stok: {{ $item->product->stock > 0 ? $item->product->stock : 'Habis' }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center border rounded-lg">
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-800 {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span
                                            class="px-4 py-1 text-center min-w-12 quantity-display">{{ $item->quantity }}</span>
                                        <button onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="px-3 py-1 text-gray-600 hover:text-gray-800"
                                            {{ $item->product->stock !== null && $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Remove Button -->
                                    <button onclick="removeItem({{ $item->id }})"
                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="mt-4 text-right">
                                <p class="text-lg font-semibold text-gray-900">
                                    Subtotal: <span class="item-subtotal">Rp
                                        {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-4">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Item</span>
                                <span class="font-medium total-items">{{ $cartItems->sum('quantity') }} item</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium total-price">Rp
                                    {{ number_format($cartItems->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="total-price">Rp
                                {{ number_format($cartItems->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}</span>
                        </div>

                        <button
                            class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Lanjut ke Checkout
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-12">
                <div class="flex flex-col items-center">
                    <svg class="w-24 h-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                        </path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-6">Belum ada produk dalam keranjang belanja Anda</p>
                    <a href="{{ route('dashboard') }}"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) return;

            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const quantityDisplay = cartItem.querySelector('.quantity-display');
            const subtotalDisplay = cartItem.querySelector('.item-subtotal');

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
                        // Update quantity display
                        quantityDisplay.textContent = newQuantity;

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
                            // Animate removal
                            cartItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            cartItem.style.opacity = '0';
                            cartItem.style.transform = 'translateX(-100%)';

                            setTimeout(() => {
                                cartItem.remove();
                                updateTotals();

                                // Check if cart is now empty
                                if (document.querySelectorAll('.cart-item').length === 0) {
                                    location.reload();
                                }
                            }, 300);

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
                            location.reload();
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

            // Update displays
            document.querySelector('.total-items').textContent = `${totalItems} item`;
            document.querySelectorAll('.total-price').forEach(el => {
                el.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            });
        }

        function updateCartCounter(count) {
            const cartCounter = document.getElementById('cart-counter');
            if (cartCounter) {
                cartCounter.textContent = count;
                if (count > 0) {
                    cartCounter.classList.remove('hidden');
                } else {
                    cartCounter.classList.add('hidden');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');

            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ?
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>` :
                `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;

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
    </script>
@endsection
