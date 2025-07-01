@extends('layouts.app')

@section('title', 'Home - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Grid Produk -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            @for ($i = 0; $i < 10; $i++)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
                        @if ($i % 2 == 0)
                            <!-- Produk Celana Putih -->
                            <img src="https://via.placeholder.com/300x300/f8f8f8/cccccc?text=White+Pants"
                                alt="Cotton T-Shirt Basic Slim Fit T-Shirt"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <!-- Produk T-Shirt Hitam -->
                            <img src="https://via.placeholder.com/300x300/2d2d2d/ffffff?text=Black+T-Shirt"
                                alt="Cotton T-Shirt Basic Slim Fit T-Shirt"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-800 mb-1">
                            Cotton T-Shirt<br>
                            <span class="font-bold">Basic Slim Fit T-Shirt</span>
                        </h3>
                        <p class="text-lg font-bold text-gray-900">Rp. 80.000</p>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Go To Shop Button -->
        <div class="flex items-center justify-between">
            <button class="flex items-center space-x-2 text-gray-800 hover:text-gray-600 transition-colors">
                <span class="text-lg font-medium">Go To Shop</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </button>

            <!-- Pagination -->
            <div class="flex space-x-2">
                <button class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="w-8 h-8 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection
