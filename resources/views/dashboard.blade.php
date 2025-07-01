@extends('layouts.app')

@section('title', 'Home - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Grid Produk -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
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
@endsection
