@extends('layouts.app')

@section('title', 'Home - ThriftHub')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Grid Produk -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            @include('partials.product-grid', ['products' => $products])
        </div>

        <!-- Loading Spinner -->
        <div id="loading-spinner" class="hidden flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-800"></div>
            <span class="ml-2 text-gray-600">Memuat produk...</span>
        </div>

        <!-- Load More Button -->
        @if ($hasMore)
            <div id="load-more-container" class="flex justify-center">
                <button id="load-more-btn" type="button"
                    class="px-6 py-3 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    Tampilkan Lebih Banyak
                </button>
            </div>
        @endif

        <!-- No More Products Message -->
        <div id="no-more-products" class="hidden text-center py-4">
            <p class="text-gray-500 text-sm">Tidak ada produk lagi untuk ditampilkan</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    <div id="dashboard-meta" data-current-page="{{ $currentPage }}" data-has-more="{{ $hasMore ? 'true' : 'false' }}">
    </div>

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

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endsection
