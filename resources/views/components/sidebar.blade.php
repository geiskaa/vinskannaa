<!-- Sidebar -->
<div class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6m-8 0V9a2 2 0 012-2h4a2 2 0 012 2v4">
                    </path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">THRIFTMART</h2>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href=""
            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('seller.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
            <span class="font-medium">DASHBOARD</span>
        </a>

        <!-- Semua Produk -->
        <a href=""
            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('seller.products') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <span class="font-medium">SEMUA PRODUK</span>
        </a>

        <!-- Order List -->
        <a href=""
            class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('seller.orders') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span class="font-medium">ORDER LIST</span>
        </a>

        <!-- Categories -->
        <div x-data="">
            {{-- <div x-data="{ open: {{ request()->routeIs('seller.categories*') ? 'true' : 'false' }} }"> --}}
            <button @click="open = !open"
                class="flex items-center justify-between w-full px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('seller.categories*') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <span class="font-medium">Categories</span>
                </div>
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="open" x-transition class="mt-2 space-y-1">
                <a href=""
                    class="flex items-center space-x-3 px-8 py-2 rounded-lg transition-colors {{ request()->routeIs('seller.categories.men') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <div class="w-2 h-2 bg-current rounded-full"></div>
                    <span class="text-sm">Men</span>
                </a>
                <a href=""
                    class="flex items-center space-x-3 px-8 py-2 rounded-lg transition-colors {{ request()->routeIs('seller.categories.women') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <div class="w-2 h-2 bg-current rounded-full"></div>
                    <span class="text-sm">Women</span>
                </a>
                <a href=""
                    class="flex items-center space-x-3 px-8 py-2 rounded-lg transition-colors {{ request()->routeIs('seller.categories.kids') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <div class="w-2 h-2 bg-current rounded-full"></div>
                    <span class="text-sm">Kids</span>
                </a>
            </div>
        </div>
    </nav>
</div>
