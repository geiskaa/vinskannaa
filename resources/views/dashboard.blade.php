@extends('layouts.app')

@section('title', 'Home - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Grid Produk -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
                        <!-- Favorite Button -->
                        @auth
                            <button onclick="toggleFavorite({{ $product->id }})"
                                class="absolute top-2 right-2 z-10 p-2 rounded-full bg-white bg-opacity-80 hover:bg-opacity-100 transition-all duration-200 shadow-sm hover:shadow-md favorite-btn transform hover:scale-110"
                                data-product-id="{{ $product->id }}"
                                data-is-favorited="{{ isset($product->is_favorited) && $product->is_favorited ? 'true' : 'false' }}">
                                <svg class="w-5 h-5 transition-all duration-300 {{ isset($product->is_favorited) && $product->is_favorited ? 'text-red-500 fill-current scale-110' : 'text-gray-400' }}"
                                    fill="{{ isset($product->is_favorited) && $product->is_favorited ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </button>
                        @endauth

                        @if ($product->image && (Str::startsWith($product->image, 'http') || Storage::disk('public')->exists($product->image)))
                            {{-- Gambar utama dari database (lokal atau URL) --}}
                            <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @elseif ($product->images && is_array($product->images) && count($product->images) > 0)
                            @php
                                $firstImage = $product->images[0];
                                $isExternal = Str::startsWith($firstImage, 'http');
                                $imageExists = !$isExternal && Storage::disk('public')->exists($firstImage);
                            @endphp

                            @if ($isExternal || $imageExists)
                                <img src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                {{-- Placeholder jika gambar tidak ditemukan --}}
                                <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        @else
                            <!-- Default placeholder jika tidak ada gambar -->
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-800 mb-1">
                            {{ Str::title(Str::limit($product->name, 30)) }}<br>
                            @if ($product->category)
                                <span class="inline-block  text-amber-800 text-xs font-semibold py-0.5 rounded-full mt-1">
                                    {{ Str::title($product->category) }}
                                </span>
                            @endif
                        </h3>

                        <p class="text-lg font-bold text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        @if ($product->stock !== null)
                            <p class="text-xs text-gray-500 mt-1">
                                Stok: {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                            </p>
                        @endif
                        @if ($product->ratings)
                            <div class="flex items-center mt-1">
                                <span class="text-yellow-400 text-xs">★</span>
                                <span class="text-xs text-gray-600 ml-1">{{ $product->ratings }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Pesan jika tidak ada produk -->
                <div class="col-span-full text-center py-12">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada produk</h3>
                        <p class="text-gray-500">Produk akan ditampilkan di sini setelah ditambahkan.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Go To Shop Button - hanya tampil jika ada produk -->
        @if ($products->count() > 0)
            <div class="flex justify-center">
                <form action="" method="GET">
                    <button type="submit"
                        class="px-6 py-3 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                        Tampilkan Lebih Banyak
                    </button>
                </form>
            </div>
        @endif

    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        function toggleFavorite(productId) {
            const btn = document.querySelector(`[data-product-id="${productId}"]`);
            const icon = btn.querySelector('svg');
            const currentIsFavorited = btn.getAttribute('data-is-favorited') === 'true';

            // Disable button temporarily
            btn.disabled = true;

            // Add loading animation
            btn.classList.add('animate-pulse');

            fetch(`/favorites/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        const newIsFavorited = data.is_favorited;
                        btn.setAttribute('data-is-favorited', newIsFavorited);

                        // Update icon appearance with smooth transition
                        if (newIsFavorited) {
                            // Favorited: red heart with fill
                            icon.classList.add('text-red-500', 'fill-current', 'scale-110');
                            icon.classList.remove('text-gray-400');
                            icon.setAttribute('fill', 'currentColor');

                            // Add bounce animation
                            icon.classList.add('animate-bounce');
                            setTimeout(() => {
                                icon.classList.remove('animate-bounce');
                            }, 600);
                        } else {
                            // Not favorited: gray heart without fill
                            icon.classList.remove('text-red-500', 'fill-current', 'scale-110');
                            icon.classList.add('text-gray-400');
                            icon.setAttribute('fill', 'none');
                        }

                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.classList.remove('animate-pulse');
                });
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

            // Toast content based on type
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
                    <div class="flex-shrink-0">
                        ${icon}
                    </div>
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

            // Trigger animation after a brief delay
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove after 4 seconds
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

    <style>
        @keyframes bounce {

            0%,
            20%,
            53%,
            80%,
            100% {
                transform: translate3d(0, 0, 0);
            }

            40%,
            43% {
                transform: translate3d(0, -8px, 0);
            }

            70% {
                transform: translate3d(0, -4px, 0);
            }

            90% {
                transform: translate3d(0, -2px, 0);
            }
        }

        .animate-bounce {
            animation: bounce 0.6s ease-in-out;
        }
    </style>
@endsection
