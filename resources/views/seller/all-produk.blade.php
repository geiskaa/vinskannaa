@extends('layouts.seller')

@section('title', 'Semua Produk - ThriftMart')
@section('page-title', 'SEMUA PRODUK')

@section('content')
    <div class="flex gap-6">
        <!-- Sidebar Kategori -->
        <div class="w-64 bg-white rounded-lg shadow-sm border border-gray-200 h-fit">
            <div class="p-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Kategori</h3>
            </div>
            <div class="p-4 space-y-2">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Baju Hitam</span>
                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded">21</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Jaket</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">32</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Celana</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">13</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Celana Pendek</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">14</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Jaket</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">06</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-700">Celana</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded">11</span>
                </div>
            </div>
        </div>

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
                <button
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
                @forelse($products ?? collect([
                ['id' => 1, 'name' => 'Baju Hitam', 'category' => 'Cotton', 'price' => 50000, 'description' => 'Baju Hitam
                Elegan berbahan 100% Cotton Premium yang lembut, adem, dan nyaman.', 'stock' => 1269, 'image' =>
                'shirt-black.jpg'],
                ['id' => 2, 'name' => 'Celana Jeans', 'category' => 'Slim', 'price' => 80000, 'description' => 'Lorem ipsum
                is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' => 'jeans-blue.jpg'],
                ['id' => 3, 'name' => 'Baju Hitam', 'category' => 'Cotton', 'price' => 50000, 'description' => 'Lorem ipsum
                is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' => 'shirt-black.jpg'],
                ['id' => 4, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Baju Hitam
                Elegan berbahan 100% Cotton Premium yang lembut, adem, dan nyaman.', 'stock' => 1269, 'image' =>
                'shirt-black.jpg'],
                ['id' => 5, 'name' => 'Lorem Ipsum', 'category' => 'Battery', 'price' => 80000, 'description' => 'Lorem
                ipsum is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' =>
                'product-generic.jpg'],
                ['id' => 6, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Lorem ipsum
                is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' => 'shirt-black.jpg'],
                ['id' => 7, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Baju Hitam
                Elegan berbahan 100% Cotton Premium yang lembut, adem, dan nyaman.', 'stock' => 1269, 'image' =>
                'shirt-black.jpg'],
                ['id' => 8, 'name' => 'Lorem Ipsum', 'category' => 'Battery', 'price' => 80000, 'description' => 'Lorem
                ipsum is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' =>
                'product-generic.jpg'],
                ['id' => 9, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Lorem ipsum
                is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' => 'shirt-black.jpg'],
                ['id' => 10, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Baju Hitam
                Elegan berbahan 100% Cotton Premium yang lembut, adem, dan nyaman.', 'stock' => 1269, 'image' =>
                'shirt-black.jpg'],
                ['id' => 11, 'name' => 'Lorem Ipsum', 'category' => 'Battery', 'price' => 80000, 'description' => 'Lorem
                ipsum is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' =>
                'product-generic.jpg'],
                ['id' => 12, 'name' => 'Baju Hitam', 'category' => 'Battery', 'price' => 50000, 'description' => 'Lorem
                ipsum is placeholder text commonly used in the graphic.', 'stock' => 1269, 'image' => 'shirt-black.jpg']
                ]) as $product)
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Product Image -->
                        <div class="relative">
                            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                @if ($product['image'] ?? false)
                                    <img src="{{ asset('storage/products/' . $product['image']) }}"
                                        alt="{{ $product['name'] }}" class="w-full h-full object-cover">
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
                                <button class="p-1 bg-white rounded-full shadow-sm hover:bg-gray-50">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $product['name'] }}</h3>
                                    <p class="text-sm text-gray-500">{{ $product['category'] }}</p>
                                </div>
                                <button class="p-1 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            <div class="mb-3">
                                <p class="text-lg font-bold text-gray-900">
                                    Rp.{{ number_format($product['price'], 0, ',', '.') }}.00</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-900 mb-1">Summary</p>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $product['description'] }}</p>
                            </div>

                            <!-- Stock Info -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    @if (str_contains($product['name'], 'Lorem'))
                                        Stok Produk
                                    @else
                                        Stok Produks
                                    @endif
                                </span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-orange-400 rounded-full" style="width: 85%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $product['stock'] }}</span>
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
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="px-3 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">1</button>
                    <button
                        class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">2</button>
                    <button
                        class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">3</button>
                    <button
                        class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">4</button>
                    <span class="px-3 py-2 text-gray-500">...</span>
                    <button
                        class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">10</button>
                    <button
                        class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center space-x-1">
                        <span>NEXT</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Script untuk dropdown menu pada card produk
        document.addEventListener('DOMContentLoaded', function() {
            // Add any JavaScript functionality here if needed
            console.log('Halaman Semua Produk loaded');
        });
    </script>
@endpush
