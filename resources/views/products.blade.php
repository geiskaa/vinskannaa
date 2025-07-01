@extends('layouts.app')

@section('title', 'Produk - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Featured Products Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Produk Celana Putih -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="aspect-[4/5] bg-gray-100 relative overflow-hidden">
                    <img src="https://via.placeholder.com/400x500/f8f8f8/cccccc?text=White+Pants" alt="White Cotton Pants"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            </div>

            <!-- Produk Celana Putih 2 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="aspect-[4/5] bg-gray-100 relative overflow-hidden">
                    <img src="https://via.placeholder.com/400x500/f8f8f8/cccccc?text=White+Pants+2" alt="White Cotton Pants"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            </div>

            <!-- Produk T-Shirt Hitam -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="aspect-[4/5] bg-gray-800 relative overflow-hidden">
                    <img src="https://via.placeholder.com/400x500/2d2d2d/ffffff?text=Black+T-Shirt"
                        alt="Black T-Shirt with Design"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <!-- Overlay design pada t-shirt -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                            <div class="text-xs">★ Design ★</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex items-center justify-between">
            <button class="flex items-center space-x-2 text-gray-800 hover:text-gray-600 transition-colors">
                <span class="text-lg font-medium">Go To Shop</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </button>

            <div class="flex space-x-2">
                <button
                    class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button
                    class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Product Categories -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Kategori Produk</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Pakaian Pria</h3>
                    <p class="text-sm text-gray-600 mt-2">T-Shirt, Kemeja, Celana</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Pakaian Wanita</h3>
                    <p class="text-sm text-gray-600 mt-2">Dress, Blouse, Rok</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 100-5H9v5zm4 0h1.5a2.5 2.5 0 100-5H13v5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Pakaian Anak</h3>
                    <p class="text-sm text-gray-600 mt-2">Kaos, Celana, Dress</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-900">Aksesoris</h3>
                    <p class="text-sm text-gray-600 mt-2">Tas, Sepatu, Topi</p>
                </div>
            </div>
        </div>
    </div>
@endsection
