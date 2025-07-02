<nav class="bg-white shadow-lg border-b border-gray-100 sticky top-0 z-50 backdrop-blur-sm bg-white/95"
    x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header utama -->
        <div class="flex items-center justify-between h-16">
            <!-- Kiri: Brand + Navigation -->
            <div class="flex items-center space-x-8">
                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            x-show="!mobileMenuOpen" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            x-show="mobileMenuOpen" x-transition>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Brand & Navigation -->
                <div class="flex items-center space-x-8">
                    <!-- Brand -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('dashboard') }}">
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent cursor-pointer">
                                ThriftHub
                            </h1>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
                                  {{ request()->routeIs('dashboard') && !request()->has('section')
                                      ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md transform scale-105'
                                      : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 hover:scale-105' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span>Products</span>
                            </div>
                        </a>

                        <a href="{{ route('about') }}"
                            class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out
                                  {{ request()->routeIs('about')
                                      ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md transform scale-105'
                                      : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 hover:scale-105' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Tentang</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tengah: Search Bar -->
            <div class="flex-1 max-w-lg mx-8 hidden md:block">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" placeholder="Cari produk favorit Anda..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm placeholder-gray-500">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <kbd
                            class="hidden sm:inline-block px-2 py-1 text-xs font-medium text-gray-500 bg-white border border-gray-200 rounded">
                            ⌘K
                        </kbd>
                    </div>
                </div>
            </div>

            <!-- Kanan: Actions & Profile -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('cart.index') }}"
                    class="relative p-2.5 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group">
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                        </path>
                    </svg>

                    @php
                        $cartCount = auth()->user()->cartItemsCount();
                    @endphp
                    @if ($cartCount > 0)
                        <span id="cart-counter"
                            class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-lg animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @else
                        <span id="cart-counter"
                            class="hidden absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-lg">
                            0
                        </span>
                    @endif
                </a>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center space-x-3 p-2 rounded-xl hover:bg-gray-50 transition-all duration-200 group">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md group-hover:shadow-lg transition-shadow">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden lg:block text-left">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 transform -translate-y-2" x-cloak
                        class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">

                        <!-- Profile Header -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="py-2">
                            <a href=""
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Edit Profile</span>
                            </a>

                            <a href="#"
                                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors group">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-indigo-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Pesanan Saya dan Wishlist</span>
                            </a>
                        </div>

                        <div class="border-t border-gray-100 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="cursor-pointer flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors group">
                                    <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4" x-cloak
            class="lg:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('dashboard') && !request()->has('section')
                              ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md'
                              : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span>Products</span>
                    </div>
                </a>

                <a href="{{ route('about') }}"
                    class="block px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          {{ request()->routeIs('about')
                              ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md'
                              : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Tentang</span>
                    </div>
                </a>

                <!-- Section Filter Mobile -->
                <div class="pt-2 border-t border-gray-100">
                    <p class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Filter by Section
                    </p>
                    <a href="{{ route('dashboard', ['section' => 'men']) }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->get('section') === 'men'
                                  ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>MEN</span>
                        </div>
                    </a>

                    <a href="{{ route('dashboard', ['section' => 'women']) }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->get('section') === 'women'
                                  ? 'bg-gradient-to-r from-pink-500 to-pink-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-pink-600 hover:bg-pink-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>WOMEN</span>
                        </div>
                    </a>

                    <a href="{{ route('dashboard', ['section' => 'kids']) }}"
                        class="block px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                              {{ request()->get('section') === 'kids'
                                  ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-green-600 hover:bg-green-50' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span>KIDS</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Filter - Only show on dashboard -->
        @if (request()->routeIs('dashboard'))
            <div class="border-t border-gray-100 py-4">
                <div class="flex space-x-2 overflow-x-auto scrollbar-hide">
                    <!-- All Products -->
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center space-x-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                              {{ !request()->has('section')
                                  ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span>ALL</span>
                    </a>

                    <!-- Men Section -->
                    <a href="{{ route('dashboard', ['section' => 'men']) }}"
                        class="group flex items-center space-x-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                              {{ request()->get('section') === 'men'
                                  ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>MEN</span>
                    </a>

                    <!-- Women Section -->
                    <a href="{{ route('dashboard', ['section' => 'women']) }}"
                        class="group flex items-center space-x-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                              {{ request()->get('section') === 'women'
                                  ? 'bg-gradient-to-r from-pink-500 to-pink-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-pink-600 hover:bg-pink-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>WOMEN</span>
                    </a>

                    <!-- Kids Section -->
                    <a href="{{ route('dashboard', ['section' => 'kids']) }}"
                        class="group flex items-center space-x-2 px-6 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 whitespace-nowrap
                              {{ request()->get('section') === 'kids'
                                  ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md'
                                  : 'text-gray-700 hover:text-green-600 hover:bg-green-50 border border-gray-200' }}">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <span>KIDS</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</nav>

<!-- Mobile Search Bar (unchanged) -->
<div class="md:hidden bg-white border-b border-gray-100 px-4 py-3">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" placeholder="Cari produk..."
            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>

@push('scripts')
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
