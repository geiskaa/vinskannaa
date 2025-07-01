@forelse ($products as $product)
    <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow">
        <div class="aspect-square bg-gray-200 relative overflow-hidden">
            <!-- Action Buttons Container -->
            @auth
                <div class="absolute top-2 right-2 z-10 flex flex-col space-y-2">
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

                    <!-- Cart Button -->
                    <button onclick="toggleCart({{ $product->id }})"
                        class="p-2 rounded-full bg-white bg-opacity-80 hover:bg-opacity-100 transition-all duration-200 shadow-sm hover:shadow-md cart-btn transform hover:scale-110"
                        data-product-id="{{ $product->id }}" data-in-cart="{{ $product->in_cart ? 'true' : 'false' }}"
                        {{ $product->stock !== null && $product->stock <= 0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5 transition-all duration-300 {{ $product->in_cart ? 'text-green-600 scale-110' : 'text-gray-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                            </path>
                        </svg>
                    </button>
                </div>
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
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                @endif
            @else
                <!-- Default placeholder jika tidak ada gambar -->
                <div class="w-full h-full flex items-center justify-center bg-gray-300">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
