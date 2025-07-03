@extends('layouts.app')

@section('title', 'Pesanan Saya - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button
                        class="tab-button py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 transition-colors active"
                        data-tab="orders">
                        Pesanan Saya
                        <span
                            class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs">{{ count($orders) }}</span>
                    </button>
                    <button
                        class="tab-button py-4 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 transition-colors"
                        data-tab="wishlist">
                        Wishlist
                        <span
                            class="ml-2 bg-gray-100 text-gray-600 py-1 px-2 rounded-full text-xs">{{ count($favorites) }}</span>
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Orders Tab -->
                <div id="orders-tab" class="tab-content">
                    @if (count($orders) > 0)
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                @php
                                    // Hitung subtotal dari items
                                    $subtotal = $order->items->sum(function ($item) {
                                        return $item->quantity * $item->price;
                                    });

                                    // Hitung pajak 11%
                                    $tax = $subtotal * 0.11;

                                    // Hitung ongkir berdasarkan shipping method
                                    $shippingCost = 0;
                                    if ($order->shipping_method) {
                                        switch (strtolower($order->shipping_method)) {
                                            case 'reguler':
                                                $shippingCost = 15000;
                                                break;
                                            case 'express':
                                                $shippingCost = 25000;
                                                break;
                                            case 'instant':
                                                $shippingCost = 35000;
                                                break;
                                            case 'same day':
                                                $shippingCost = 50000;
                                                break;
                                            default:
                                                $shippingCost = 15000;
                                        }
                                    }

                                    // Total seharusnya = subtotal + tax + shipping
                                    $calculatedTotal = $subtotal + $tax + $shippingCost;

                                    // Cek apakah order ini sudah diberi rating
                                    $hasRated = false;
                                    if ($order->order_status == 'selesai') {
                                        $hasRated = \App\Models\ProductRating::where('order_id', $order->id)
                                            ->where('user_id', auth()->id())
                                            ->exists();
                                    }
                                @endphp

                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_id }}
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $statusClasses = [
                                                    'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                                    'dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                                    'diproses' => 'bg-indigo-100 text-indigo-800',
                                                    'dikirim' => 'bg-purple-100 text-purple-800',
                                                    'selesai' => 'bg-green-100 text-green-800',
                                                    'dibatalkan' => 'bg-red-100 text-red-800',
                                                ];

                                                $statusLabels = [
                                                    'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                                    'dikonfirmasi' => 'Dikonfirmasi',
                                                    'diproses' => 'Diproses',
                                                    'dikirim' => 'Dikirim',
                                                    'selesai' => 'Selesai',
                                                    'dibatalkan' => 'Dibatalkan',
                                                ];

                                                $statusClass =
                                                    $statusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                                                $statusLabel =
                                                    $statusLabels[$order->order_status] ??
                                                    ucfirst(str_replace('_', ' ', $order->order_status));
                                            @endphp

                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>

                                        </div>

                                    </div>

                                    <!-- Order Items -->
                                    <div class="space-y-2 mb-4">
                                        @foreach ($order->items as $item)
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    @if (
                                                        $item->product->image &&
                                                            (Str::startsWith($item->product->image, 'http') || Storage::disk('public')->exists($item->product->image)))
                                                        {{-- Gambar utama dari database (lokal atau URL) --}}
                                                        <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            class="w-full h-full object-cover rounded-lg">
                                                    @elseif ($item->product->images && is_array($item->product->images) && count($item->product->images) > 0)
                                                        @php
                                                            $firstImage = $item->product->images[0];
                                                            $isExternal = Str::startsWith($firstImage, 'http');
                                                            $imageExists =
                                                                !$isExternal &&
                                                                Storage::disk('public')->exists($firstImage);
                                                        @endphp

                                                        @if ($isExternal || $imageExists)
                                                            <img src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="w-full h-full object-cover rounded-lg">
                                                        @else
                                                            <svg class="w-6 h-6 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    @endif
                                                </div>

                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}</h4>
                                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="border-t pt-3 space-y-2">
                                        <!-- Subtotal -->
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600">Subtotal ({{ count($order->items) }} item)</span>
                                            <span class="text-gray-900">Rp
                                                {{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>

                                        <!-- Shipping -->
                                        @if ($order->shipping_method)
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-600">
                                                    Ongkir ({{ ucfirst($order->shipping_method) }})
                                                </span>
                                                <span class="text-gray-900">Rp
                                                    {{ number_format($shippingCost, 0, ',', '.') }}</span>
                                            </div>
                                        @endif

                                        <!-- Tax -->
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600">Pajak (11%)</span>
                                            <span class="text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                                        </div>

                                        <!-- Total -->
                                        <div class="flex justify-between items-center pt-2 border-t">
                                            <span class="text-lg font-bold text-gray-900">Total</span>
                                            <span class="text-lg font-bold text-gray-900">
                                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex justify-end space-x-2 mt-3">
                                            <button onclick="viewOrderDetail('{{ $order->id }}')"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                Lihat Detail
                                            </button>

                                            @if ($order->order_status == 'selesai' && !$hasRated)
                                                <button onclick="rateOrder('{{ $order->id }}')"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                                    Beri Rating
                                                </button>
                                            @elseif ($order->order_status == 'selesai' && $hasRated)
                                                <button disabled
                                                    class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">
                                                    Sudah Diberi Rating
                                                </button>
                                            @endif

                                            @if ($order->order_status == 'menunggu_konfirmasi')
                                                <button onclick="cancelOrder('{{ $order->id }}')"
                                                    class="px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-md hover:bg-red-50">
                                                    Batalkan
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty Orders -->
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pesanan</h3>
                            <p class="text-gray-500 mb-4">Pesanan Anda akan muncul di sini setelah melakukan pembelian.</p>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                Mulai Berbelanja
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Wishlist Tab -->
                <div id="wishlist-tab" class="tab-content hidden">
                    @if (count($favorites) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($favorites as $favorite)
                                <div
                                    class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow border border-gray-200">
                                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
                                        <!-- Remove from Wishlist Button -->
                                        <button onclick="removeFavorite(this, '{{ $favorite->id }}')"
                                            class="favorite-remove-btn absolute top-2 right-2 z-10 p-2 rounded-full bg-white bg-opacity-80 hover:bg-opacity-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                            <span class="icon-wrapper">
                                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                                                </svg>
                                            </span>
                                        </button>


                                        @if ($favorite->product)
                                            @php
                                                $product = $favorite->product;
                                                $hasImage =
                                                    $product->image &&
                                                    (Str::startsWith($product->image, 'http') ||
                                                        Storage::disk('public')->exists($product->image));
                                                $hasImagesArray =
                                                    $product->images &&
                                                    is_array($product->images) &&
                                                    count($product->images) > 0;
                                            @endphp

                                            @if ($hasImage)
                                                <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @elseif ($hasImagesArray)
                                                @php
                                                    $firstImage = $product->images[0];
                                                    $isExternal = Str::startsWith($firstImage, 'http');
                                                    $imageExists =
                                                        !$isExternal && Storage::disk('public')->exists($firstImage);
                                                @endphp

                                                @if ($isExternal || $imageExists)
                                                    <img src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                                        alt="{{ $product->name }}"
                                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                                        <svg class="w-16 h-16 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                                    <svg class="w-16 h-16 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        @endif

                                    </div>

                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-800 mb-2">
                                            {{ Str::title(Str::limit($favorite->product->name, 30)) }}
                                        </h3>
                                        @if ($favorite->product->category)
                                            <span
                                                class="inline-block text-amber-800 text-xs font-semibold py-0.5 px-2 rounded-full mb-2 bg-amber-100">
                                                {{ Str::title($favorite->product->category) }}
                                            </span>
                                        @endif
                                        <p class="text-lg font-bold text-gray-900 mb-2">
                                            Rp {{ number_format($favorite->product->price, 0, ',', '.') }}
                                        </p>
                                        @if ($favorite->product->stock !== null)
                                            <p class="text-xs text-gray-500 mb-3">
                                                Stok:
                                                {{ $favorite->product->stock > 0 ? $favorite->product->stock : 'Habis' }}
                                            </p>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            <button
                                                onclick="addToCart('{{ $favorite->product->id }}', '{{ $favorite->product->price }}')"
                                                class="cursor-pointer flex-1 px-3 py-2 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-700 transition-colors
                                                    {{ $favorite->product->stock !== null && $favorite->product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $favorite->product->stock !== null && $favorite->product->stock <= 0 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                                                    </path>
                                                </svg>
                                                {{ $favorite->product->stock !== null && $favorite->product->stock <= 0 ? 'Habis' : 'Keranjang' }}
                                            </button>
                                            <button onclick="viewProduct('{{ $favorite->product->id }}')"
                                                class="cursor-pointer  px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                Lihat
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty Wishlist -->
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Wishlist kosong</h3>
                            <p class="text-gray-500 mb-4">Tambahkan produk favorit Anda ke wishlist untuk melihatnya di
                                sini.</p>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md hover:bg-gray-700">
                                Jelajahi Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;

                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
                        btn.classList.add('text-gray-500', 'border-transparent');
                    });

                    // Add active class to clicked button
                    this.classList.add('active', 'text-blue-600', 'border-blue-600');
                    this.classList.remove('text-gray-500', 'border-transparent');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show selected tab content
                    document.getElementById(tabName + '-tab').classList.remove('hidden');
                });
            });

            // Set initial active tab
            document.querySelector('.tab-button.active').classList.add('text-blue-600', 'border-blue-600');
            document.querySelector('.tab-button.active').classList.remove('text-gray-500', 'border-transparent');
        });

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
            toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

            document.getElementById('toast-container').appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Order functions
        function viewOrderDetail(orderId) {
            window.location.href = `/pesanan-saya/${orderId}`;
        }

        function cancelOrder(orderId) {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                fetch(`/pesanan-saya/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Pesanan berhasil dibatalkan', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('Gagal membatalkan pesanan', 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function rateOrder(orderId) {
            window.location.href = `/pesanan-saya/${orderId}/rate`;
        }

        // Wishlist functions
        function removeFavorite(button, favoriteId) {
            if (confirm('Hapus dari wishlist?')) {
                // Ganti ikon dengan spinner
                const iconWrapper = button.querySelector('.icon-wrapper');
                iconWrapper.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        `;
                button.disabled = true;

                fetch(`/favorites/${favoriteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Produk dihapus dari wishlist', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showToast('Gagal menghapus dari wishlist', 'error');
                            resetRemoveButton(button);
                        }
                    })
                    .catch(error => {
                        showToast('Terjadi kesalahan', 'error');
                        resetRemoveButton(button);
                    });
            }
        }

        function resetRemoveButton(button) {
            const iconWrapper = button.querySelector('.icon-wrapper');
            iconWrapper.innerHTML = `
        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
        </svg>
    `;
            button.disabled = false;
        }


        function addToCart(productId, price) {
            fetch(`/cart/add`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1,
                        price: price
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Produk ditambahkan ke keranjang', 'success');
                    } else {
                        showToast('Gagal menambahkan ke keranjang', 'error');
                    }
                })
                .catch(error => {
                    showToast('Terjadi kesalahan', 'error');
                });
        }

        function viewProduct(productId) {
            window.location.href = `/products/${productId}`;
        }
    </script>
@endsection
