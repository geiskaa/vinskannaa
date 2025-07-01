<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header utama -->
        <div class="flex items-center justify-between py-4">
            <!-- Hamburger Menu & Brand -->
            <div class="flex items-center space-x-4">
                <button class="lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-8">
                    <span class="text-xl font-bold tracking-wider">Beranda</span>
                    <span class="text-xl font-bold tracking-wider">Produk</span>
                    <span class="text-xl font-bold tracking-wider">Tentang</span>
                </div>
            </div>

            <!-- Logo tengah -->
            <div class="hidden lg:block">
                <div class="w-12 h-12 bg-black transform rotate-45"></div>
            </div>

            <!-- Icon kanan -->
            <div class="flex items-center space-x-4">
                <button class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <div class="flex items-center bg-gray-800 rounded-full px-4 py-2 space-x-2">
                    <span class="text-white text-sm">Cart</span>
                    <span
                        class="bg-white text-gray-800 rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium">0</span>
                </div>

                <button class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu navigasi -->
        @if (!request()->is('about'))
            <div class="border-t py-4">
                <div class="flex space-x-8">
                    <a href=""
                        class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md {{ request()->routeIs('home') ? 'bg-gray-200' : '' }}">
                        MEN
                    </a>
                    <a href=""
                        class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md {{ request()->routeIs('products') ? 'bg-gray-200' : '' }}">
                        WOMEN
                    </a>
                    <a href=""
                        class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md {{ request()->routeIs('about') ? 'bg-gray-200' : '' }}">
                        KIDS
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="mt-4">
                    <div class="relative max-w-md">
                        <input type="text" placeholder="Search"
                            class="w-full bg-gray-200 border-0 rounded-lg py-3 px-4 pr-10 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</nav>
