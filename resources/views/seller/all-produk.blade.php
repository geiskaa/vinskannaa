@extends('layouts.seller')

@section('title', 'Semua Produk - ThriftMart')
@section('page-title', 'SEMUA PRODUK')

@section('content')
    <div class="flex gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- Header dengan Tombol Tambah Produk -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">SEMUA PRODUK</h1>
                    <nav class="text-sm text-gray-500 mt-1">
                        <span>Home</span>
                        <span class="mx-1">></span>
                        <span>Semua Produk</span>
                    </nav>
                </div>
                <button onclick="window.location.href='/seller/tambah-produk'"
                    class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>TAMBAHKAN PRODUK</span>
                </button>
            </div>

            <!-- Grid Produk -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Product Image -->
                        <div class="relative">
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                @php
                                    // Decode images jika masih dalam format JSON string
                                    $images = is_array($product->images)
                                        ? $product->images
                                        : json_decode($product->images, true);

                                    $imageUrl = null;

                                    // Handle single image
                                    if ($product->image) {
                                        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $product->image;
                                        } else {
                                            $imageUrl = asset('storage/' . $product->image);
                                        }
                                    }
                                    // Handle multiple images
                                    elseif ($images && is_array($images) && count($images) > 0) {
                                        $firstImage = $images[0];
                                        if (filter_var($firstImage, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $firstImage;
                                        } else {
                                            // perhatikan slash di sini
                                            $imageUrl = asset('storage/' . ltrim($firstImage, '/'));
                                        }
                                    }
                                @endphp


                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <!-- Placeholder untuk gambar produk -->
                                    <div class="w-20 h-20 bg-gray-800 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <!-- Menu Button -->
                            <div class="absolute top-2 right-2">
                                <div class="relative">
                                    <button onclick="toggleDropdown({{ $product->id }})"
                                        class="p-1 bg-white rounded-full shadow-sm hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                            </path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div id="dropdown-{{ $product->id }}"
                                        class="hidden absolute right-0 mt-1 w-32 bg-white rounded-md shadow-lg z-10 border border-gray-200">
                                        <div class="py-1">
                                            <a href="/seller/edit-produk/{{ $product->id }}"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                            <a href="/seller/delete-produk/{{ $product->id }}"
                                                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $product->category }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p class="text-lg font-bold text-gray-900">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-900 mb-1">Summary</p>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ Str::limit($product->description, 100) }}
                                </p>
                            </div>

                            <!-- Stock Info -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Stok Produk</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        @php
                                            $stockPercentage =
                                                $product->stock > 0 ? min(($product->stock / 100) * 100, 100) : 0;
                                        @endphp
                                        <div class="h-full bg-orange-400 rounded-full"
                                            style="width: {{ $stockPercentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $product->stock }}</span>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Dibuat: {{ $product->created_at->format('d M Y') }}</span>
                                    @if ($product->averageRating())
                                        <span>Rating: {{ number_format($product->averageRating(), 1) }}/5</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm">Belum ada produk yang ditambahkan</p>
                            <button onclick="window.location.href='/seller/tambah-produk'"
                                class="mt-4 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                Tambahkan Produk Pertama
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="mt-8 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <span
                                class="px-3 py-2 bg-gray-200 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                                PREV
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}"
                                class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>PREV</span>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <span
                                    class="px-3 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}"
                                class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center space-x-1">
                                <span>NEXT</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span
                                class="px-3 py-2 bg-gray-200 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed flex items-center space-x-1">
                                <span>NEXT</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>

                <!-- Pagination Info -->
                <div class="mt-4 text-center text-sm text-gray-600">
                    Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari
                    {{ $products->total() }} produk
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script untuk dropdown menu pada card produk
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Halaman Semua Produk loaded');

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(event.target) && !event.target.closest('button')) {
                        dropdown.classList.add('hidden');
                    }
                });
            });
        });

        function toggleDropdown(productId) {
            const dropdown = document.getElementById(`dropdown-${productId}`);
            const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');

            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.add('hidden');
                }
            });

            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }
    </script>
@endpush
