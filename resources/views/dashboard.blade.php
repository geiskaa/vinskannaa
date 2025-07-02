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

    <script>
        let currentPage = {{ $currentPage }};
        let hasMore = {{ $hasMore ? 'true' : 'false' }};
        let isLoading = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search functionality
            initializeSearch();

            // Debug existing products (keep existing code)
            document.querySelectorAll('.favorite-btn').forEach(btn => {
                console.log('Favorite State:', btn.dataset.productId, btn.dataset.isFavorited);
            });

            document.querySelectorAll('.cart-btn').forEach(btn => {
                console.log('Cart State:', btn.dataset.productId, btn.dataset.inCart);
            });

            // Load More Button Event (keep existing code)
            const loadMoreBtn = document.getElementById('load-more-btn');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', loadMoreProducts);
            }
        });
        let searchTimeout;
        let currentSearchQuery = '';

        function initializeSearch() {
            // Initialize both desktop and mobile search inputs
            const desktopSearchInput = document.querySelector('.max-w-lg input[type="text"]');
            const mobileSearchInput = document.querySelector('.md\\:hidden input[type="text"]');

            if (desktopSearchInput) {
                setupSearchInput(desktopSearchInput);
            }

            if (mobileSearchInput) {
                setupSearchInput(mobileSearchInput);
            }
        }

        function setupSearchInput(input) {
            // Add search event listeners
            input.addEventListener('input', handleSearchInput);
            input.addEventListener('keydown', handleSearchKeydown);

            // Add search icon click functionality
            const searchContainer = input.closest('.relative');
            if (searchContainer) {
                const searchIcon = searchContainer.querySelector('svg');
                if (searchIcon) {
                    searchIcon.style.cursor = 'pointer';
                    searchIcon.addEventListener('click', () => performSearch(input.value));
                }
            }
        }

        function handleSearchInput(event) {
            const query = event.target.value.trim();

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Debounce search - wait 500ms after user stops typing
            searchTimeout = setTimeout(() => {
                if (query.length >= 2 || query.length === 0) {
                    performSearch(query);
                }
            }, 500);
        }

        function handleSearchKeydown(event) {
            // Handle Enter key press
            if (event.key === 'Enter') {
                event.preventDefault();
                const query = event.target.value.trim();
                performSearch(query);
            }

            // Handle Escape key to clear search
            if (event.key === 'Escape') {
                event.target.value = '';
                performSearch('');
            }
        }

        function performSearch(query) {
            if (currentSearchQuery === query) {
                return; // Avoid duplicate searches
            }

            currentSearchQuery = query;
            currentPage = 1;
            // Show loading state
            showSearchLoading();

            // Get current section from URL
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'all';

            // Build search URL
            const searchParams = new URLSearchParams();
            if (query) {
                searchParams.set('search', query);
            }
            if (section !== 'all') {
                searchParams.set('section', section);
            }
            searchParams.set('page', '1');

            const searchUrl = `/dashboard?${searchParams.toString()}`;

            console.log('Search Request:', {
                query: query,
                section: section,
                url: searchUrl
            });

            // Perform search request
            fetch(searchUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Search request failed');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Search Response:', data);

                    if (data.success) {
                        // Update products grid
                        updateProductsGrid(data.html);

                        // Update pagination state
                        currentPage = data.currentPage;
                        hasMore = data.hasMore;

                        console.log('Search completed, updated state:', {
                            currentPage: currentPage,
                            hasMore: hasMore,
                            total: data.total || 'unknown'
                        });

                        // Update load more button visibility
                        updateLoadMoreButton();

                        // Update URL without page reload
                        if (query) {
                            const newUrl = new URL(window.location);
                            newUrl.searchParams.set('search', query);
                            if (section !== 'all') {
                                newUrl.searchParams.set('section', section);
                            }
                            newUrl.searchParams.delete('page');
                            window.history.replaceState({}, '', newUrl);
                        } else {
                            // Clear search from URL
                            const newUrl = new URL(window.location);
                            newUrl.searchParams.delete('search');
                            if (section === 'all') {
                                newUrl.searchParams.delete('section');
                            }
                            newUrl.searchParams.delete('page');
                            window.history.replaceState({}, '', newUrl);
                        }

                        // Show search results message
                        showSearchResults(query, data.products ? data.products.length : 0);
                    } else {
                        throw new Error('Invalid search response');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showToast('Gagal melakukan pencarian. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    hideSearchLoading();
                });
        }

        function showSearchLoading() {
            const productsGrid = document.getElementById('products-grid');
            const loadingSpinner = document.getElementById('loading-spinner');

            if (productsGrid) {
                productsGrid.style.opacity = '0.5';
            }

            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }
        }

        function hideSearchLoading() {
            const productsGrid = document.getElementById('products-grid');
            const loadingSpinner = document.getElementById('loading-spinner');

            if (productsGrid) {
                productsGrid.style.opacity = '1';
            }

            if (loadingSpinner) {
                loadingSpinner.classList.add('hidden');
            }
        }

        function updateProductsGrid(html) {
            const productsGrid = document.getElementById('products-grid');
            if (productsGrid && html) {
                productsGrid.innerHTML = html;
            }
        }

        function updateLoadMoreButton() {
            const loadMoreContainer = document.getElementById('load-more-container');
            const noMoreProducts = document.getElementById('no-more-products');

            if (hasMore) {
                if (loadMoreContainer) loadMoreContainer.classList.remove('hidden');
                if (noMoreProducts) noMoreProducts.classList.add('hidden');
            } else {
                if (loadMoreContainer) loadMoreContainer.classList.add('hidden');
                if (noMoreProducts) noMoreProducts.classList.remove('hidden');
            }
        }

        function showSearchResults(query, count) {
            if (query) {
                const message = count > 0 ?
                    `Ditemukan ${count} produk untuk "${query}"` :
                    `Tidak ada produk ditemukan untuk "${query}"`;

                showToast(message, count > 0 ? 'success' : 'info');
            }
        }


        function loadMoreProducts() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            const loadMoreBtn = document.getElementById('load-more-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const productsGrid = document.getElementById('products-grid');

            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const section = urlParams.get('section') || 'all';
            const search = urlParams.get('search') || '';

            console.log('Load More Request:', {
                currentPage: currentPage,
                nextPage: currentPage + 1,
                section: section,
                search: search,
                hasMore: hasMore
            });

            // Show loading state
            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = `
        <div class="flex items-center">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
            Memuat...
        </div>
    `;
            loadingSpinner.classList.remove('hidden');

            // Build URL parameters - pastikan parameter sesuai dengan yang diharapkan controller
            const params = new URLSearchParams();
            params.set('page', parseInt(currentPage, 10) + 1);

            if (section !== 'all') {
                params.set('section', section);
            }
            if (search) {
                params.set('search', search); // Pastikan menggunakan 'search', bukan 'q'
            }

            console.log('Fetch URL:', `/dashboard?${params.toString()}`);

            // Fetch with all parameters
            fetch(`/dashboard?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Load More Response:', data);

                    if (data.success && data.html) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.html;

                        const newProducts = tempDiv.children;
                        let addedCount = 0;

                        while (newProducts.length > 0) {
                            productsGrid.appendChild(newProducts[0]);
                            addedCount++;
                        }

                        // Update state
                        currentPage = data.currentPage;
                        hasMore = data.hasMore;

                        console.log('Updated state:', {
                            currentPage: currentPage,
                            hasMore: hasMore,
                            addedProducts: addedCount,
                            totalProducts: data.total || 'unknown'
                        });

                        // Update load more button visibility
                        updateLoadMoreButton();

                        const message = search ?
                            `${addedCount} produk lagi dimuat untuk "${search}"` :
                            `${addedCount} produk baru dimuat`;

                        showToast(message, 'success');
                    } else {
                        console.error('Invalid response data:', data);
                        throw new Error('Invalid response data');
                    }
                })
                .catch(error => {
                    console.error('Error loading more products:', error);
                    showToast('Gagal memuat produk. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    isLoading = false;
                    loadingSpinner.classList.add('hidden');

                    if (hasMore) {
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.textContent = 'Tampilkan Lebih Banyak';
                    }
                });
        }

        function updateLoadMoreButton() {
            const loadMoreContainer = document.getElementById('load-more-container');
            const noMoreProducts = document.getElementById('no-more-products');

            console.log('Updating load more button, hasMore:', hasMore);

            if (hasMore) {
                if (loadMoreContainer) loadMoreContainer.classList.remove('hidden');
                if (noMoreProducts) noMoreProducts.classList.add('hidden');
            } else {
                if (loadMoreContainer) loadMoreContainer.classList.add('hidden');
                if (noMoreProducts) noMoreProducts.classList.remove('hidden');
            }
        }

        function toggleFavorite(productId) {
            const btn = document.querySelector(`[data-product-id="${productId}"].favorite-btn`);
            const icon = btn.querySelector('svg');
            const currentIsFavorited = btn.getAttribute('data-is-favorited') === 'true';
            console.log(`Product ID: ${productId}, is_favorited: ${currentIsFavorited}`);

            // Disable button temporarily
            btn.disabled = true;
            btn.classList.add('animate-pulse');

            fetch(`/favorites/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        const newIsFavorited = data.is_favorited;
                        btn.setAttribute('data-is-favorited', newIsFavorited);

                        // Update icon appearance with smooth transition
                        if (newIsFavorited) {
                            // Favorited: red heart with fill
                            icon.classList.add('text-red-500', 'fill-current', 'scale-110');
                            icon.classList.remove('text-gray-400');
                            icon.setAttribute('fill', 'currentColor');

                            // Add bounce animation
                            icon.classList.add('animate-bounce');
                            setTimeout(() => {
                                icon.classList.remove('animate-bounce');
                            }, 600);
                        } else {
                            // Not favorited: gray heart without fill
                            icon.classList.remove('text-red-500', 'fill-current', 'scale-110');
                            icon.classList.add('text-gray-400');
                            icon.setAttribute('fill', 'none');
                        }

                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.classList.remove('animate-pulse');
                });
        }

        function toggleCart(productId) {
            const btn = document.querySelector(`[data-product-id="${productId}"].cart-btn`);
            const icon = btn.querySelector('svg');
            const currentInCart = btn.getAttribute('data-in-cart') === 'true';

            // Check if button is disabled (out of stock)
            if (btn.disabled) {
                showToast('Produk sedang habis stok', 'error');
                return;
            }

            // Disable button temporarily
            btn.disabled = true;
            btn.classList.add('animate-pulse');

            fetch(`/cart/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        const newInCart = data.in_cart;
                        btn.setAttribute('data-in-cart', newInCart);

                        // Update icon appearance with smooth transition
                        if (newInCart) {
                            // In cart: green cart with scale
                            icon.classList.add('text-green-600', 'scale-110');
                            icon.classList.remove('text-gray-400');

                            // Add shake animation
                            icon.classList.add('animate-shake');
                            setTimeout(() => {
                                icon.classList.remove('animate-shake');
                            }, 600);
                        } else {
                            // Not in cart: gray cart
                            icon.classList.remove('text-green-600', 'scale-110');
                            icon.classList.add('text-gray-400');
                        }

                        // Update cart counter in navbar if exists
                        updateCartCounter(data.cart_count);
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan jaringan', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.classList.remove('animate-pulse');
                });
        }

        function updateCartCounter(count) {
            const cartCounter = document.getElementById('cart-counter');
            if (cartCounter) {
                cartCounter.textContent = count;
                if (count > 0) {
                    cartCounter.classList.remove('hidden');
                    // Add bounce animation to counter
                    cartCounter.classList.add('animate-bounce');
                    setTimeout(() => {
                        cartCounter.classList.remove('animate-bounce');
                    }, 600);
                } else {
                    cartCounter.classList.add('hidden');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

            // Toast content based on type
            let bgColor, icon;

            switch (type) {
                case 'success':
                    bgColor = 'bg-green-500';
                    icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>`;
                    break;
                case 'error':
                    bgColor = 'bg-red-500';
                    icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`;
                    break;
                case 'info':
                    bgColor = 'bg-blue-500';
                    icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
                    break;
                default:
                    bgColor = 'bg-gray-500';
                    icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
            }

            toast.innerHTML = `
        <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-64 max-w-sm">
            <div class="flex-shrink-0">
                ${icon}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="removeToast(this)" class="flex-shrink-0 ml-2 text-white hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

            toastContainer.appendChild(toast);

            // Trigger animation after a brief delay
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove after 4 seconds
            setTimeout(() => {
                removeToast(toast);
            }, 4000);
        }

        function removeToast(element) {
            const toast = element.closest('.transform');
            if (toast) {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');

                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }
        }

        document.addEventListener('keydown', function(event) {
            if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
                event.preventDefault();

                // Focus on search input
                const searchInput = document.querySelector('.max-w-lg input[type="text"]') ||
                    document.querySelector('.md\\:hidden input[type="text"]');

                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });
    </script>

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
