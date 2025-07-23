<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vinskanna')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')
    @stack('scripts')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/cart-function.js') }}"></script>
    <script src="{{ asset('js/favorite-function.js') }}"></script>
    <script src="{{ asset('js/order-function.js') }}"></script>
    <script src="{{ asset('js/pagination-function.js') }}"></script>
    <script src="{{ asset('js/rating-function.js') }}"></script>
    <script src="{{ asset('js/search-function.js') }}"></script>
    <script src="{{ asset('js/tab-function.js') }}"></script>
    <script src="{{ asset('js/animation-function.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-initialize berdasarkan halaman
            @if (request()->routeIs('dashboard'))
                initializeSearch()
            @elseif (request()->routeIs('favorites'))
                initializeFavorites();
            @elseif (request()->routeIs('orders*'))
                initializeOrderTabs();
            @elseif (request()->routeIs('carts'))
                initializeCart();
            @elseif (request()->routeIs('pesananSaya'))
                initializeTabs();
            @endif
        });
    </script>

</body>

</html>
