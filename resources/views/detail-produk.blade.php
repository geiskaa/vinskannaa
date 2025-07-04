@extends('layouts.app')
@section('title', $product->name . ' - ThriftHub')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-8">
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Beranda</a>
            <span>></span>
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Produk</a>
            <span>></span>
            @if ($product->category)
                <span class="capitalize">{{ $product->category }}</span>
                <span>></span>
            @endif
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left Column - Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    @if ($product->image && (Str::startsWith($product->image, 'http') || Storage::disk('public')->exists($product->image)))
                        <img id="main-image"
                            src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @elseif ($product->images && is_array($product->images) && count($product->images) > 0)
                        @php
                            $firstImage = $product->images[0];
                            $isExternal = Str::startsWith($firstImage, 'http');
                            $imageExists = !$isExternal && Storage::disk('public')->exists($firstImage);
                        @endphp
                        @if ($isExternal || $imageExists)
                            <img id="main-image" src="{{ $isExternal ? $firstImage : asset('storage/' . $firstImage) }}"
                                alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Images -->
                @if ($product->images && is_array($product->images) && count($product->images) > 1)
                    <div class="flex space-x-2 overflow-x-auto">
                        @foreach ($product->images as $index => $image)
                            @php
                                $isExternal = Str::startsWith($image, 'http');
                                $imageExists = !$isExternal && Storage::disk('public')->exists($image);
                            @endphp
                            @if ($isExternal || $imageExists)
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300 cursor-pointer thumbnail-image {{ $index === 0 ? 'border-gray-400' : '' }}"
                                    onclick="changeMainImage('{{ $isExternal ? $image : asset('storage/' . $image) }}', this)">
                                    <img src="{{ $isExternal ? $image : asset('storage/' . $image) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="flex space-x-4">
                    <button onclick="history.back()" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span>Kembali</span>
                    </button>
                    <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span>Selanjutnya</span>
                    </button>
                </div>
            </div>

            <!-- Right Column - Product Information -->
            <div class="space-y-6">
                <!-- Product Title and Category -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    @if ($product->category)
                        <span class="inline-block bg-amber-100 text-amber-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ Str::title($product->category) }}
                        </span>
                    @endif
                </div>

                <!-- Price -->
                <div class="text-3xl font-bold text-gray-900">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                <!-- Stock and Rating -->
                <div class="flex items-center space-x-4">
                    @if ($product->stock !== null)
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Stok:</span>
                            <span
                                class="text-sm font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                            </span>
                        </div>
                    @endif
                    @if ($product->ratings)
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ $product->ratings }}</span>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if ($product->description)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @else
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Produk berkualitas tinggi dengan desain yang menarik. Cocok untuk berbagai kebutuhan dan
                            aktivitas sehari-hari.
                        </p>
                    </div>
                @endif

                <!-- Product Attributes -->
                @if ($product->size || $product->color || $product->material)
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Spesifikasi</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @if ($product->size)
                                <div>
                                    <span class="text-sm text-gray-600">Ukuran:</span>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->size }}</span>
                                </div>
                            @endif
                            @if ($product->color)
                                <div>
                                    <span class="text-sm text-gray-600">Warna:</span>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->color }}</span>
                                </div>
                            @endif
                            @if ($product->material)
                                <div>
                                    <span class="text-sm text-gray-600">Material:</span>
                                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $product->material }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                @auth
                    <div class="flex space-x-4 pt-6">
                        <!-- Add to Cart Button -->
                        <button onclick="toggleCart({{ $product->id }}, 'detail')"
                            class="flex-1 bg-gray-900 text-white py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center justify-center space-x-2 cart-btn disabled:opacity-50 disabled:cursor-not-allowed"
                            data-product-id="{{ $product->id }}" data-in-cart="{{ $product->in_cart ? 'true' : 'false' }}"
                            {{ $product->stock !== null && $product->stock <= 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                                </path>
                            </svg>
                            <span>Tambah ke Keranjang</span>
                        </button>

                        <!-- Favorite Button -->
                        <button onclick="toggleFavorite({{ $product->id }})"
                            class="p-2 rounded-full bg-white bg-opacity-80 hover:bg-opacity-100 transition-all duration-200 shadow-sm hover:shadow-md favorite-btn transform hover:scale-110"
                            data-product-id="{{ $product->id }}"
                            data-is-favorited="{{ $product->is_favorited ? 'true' : 'false' }}">
                            <svg class="w-5 h-5 transition-all duration-300 {{ $product->is_favorited ? 'text-red-500 fill-current scale-110' : 'text-gray-400' }}"
                                fill="{{ $product->is_favorited ? 'currentColor' : 'none' }}" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="pt-6">
                        <a href="{{ route('login') }}"
                            class="w-full bg-gray-900 text-white py-3 px-6 rounded-lg hover:bg-gray-800 transition-colors duration-200 flex items-center justify-center space-x-2">
                            <span>Login untuk Berbelanja</span>
                        </a>
                    </div>
                @endauth


            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection
