<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header utama -->
        <div class="flex items-center justify-between py-4">
            <!-- Kiri: Hamburger Menu + Navigasi -->
            <div class="flex items-center space-x-4">
                <button class="lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}"
                        class="text-xl font-bold tracking-wider px-3 py-1 rounded-md transition-colors
                       {{ request()->routeIs('dashboard') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-100' }}">
                        Products
                    </a>
                    <a href="{{ route('about') }}"
                        class="text-xl font-bold tracking-wider px-3 py-1 rounded-md transition-colors
                       {{ request()->routeIs('about') ? 'bg-gray-200 text-black' : 'text-gray-700 hover:bg-gray-100' }}">
                        Tentang
                    </a>

                    <!-- Search bar -->
                    <div class="relative max-w-xs ml-4 hidden md:block">
                        <input type="text" placeholder="Search"
                            class="w-full bg-gray-200 border-0 rounded-lg py-2 px-3 pr-10 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kanan: Icon -->
            <div class="flex items-center space-x-4">

                <!-- Icon Keranjang -->
                <div class="relative">
                    <button class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.34 2.68A1 1 0 007 17h10a1 1 0 00.93-.63L19 13M16 21a1 1 0 100-2 1 1 0 000 2zm-8 0a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                    </button>
                    <span
                        class="absolute -top-1 -right-1 bg-white text-gray-800 text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center shadow">
                        0
                    </span>
                </div>

                <button class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menu Navigasi Tambahan -->
        @if (!request()->is('about'))
            <div class="border-t py-4">
                <div class="flex space-x-8">
                    <a href="" class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md">
                        MEN
                    </a>
                    <a href="" class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md">
                        WOMEN
                    </a>
                    <a href="" class="text-gray-700 hover:text-gray-900 font-medium px-4 py-2 rounded-md">
                        KIDS
                    </a>
                </div>
            </div>
        @endif
    </div>
</nav>
