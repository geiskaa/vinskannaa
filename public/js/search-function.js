/**
 * Search functionality for dashboard
 */

let searchTimeout;
let currentSearchQuery = "";

function initializeSearch() {
    // Initialize both desktop and mobile search inputs
    const desktopSearchInput = document.querySelector(
        '.max-w-lg input[type="text"]'
    );
    const mobileSearchInput = document.querySelector(
        '.md\\:hidden input[type="text"]'
    );

    if (desktopSearchInput) {
        setupSearchInput(desktopSearchInput);
    }

    if (mobileSearchInput) {
        setupSearchInput(mobileSearchInput);
    }

    // Initialize global search hotkey (Ctrl/Cmd + K)
    initializeSearchHotkey();
}

function setupSearchInput(input) {
    // Add search event listeners
    input.addEventListener("input", handleSearchInput);
    input.addEventListener("keydown", handleSearchKeydown);

    // Add search icon click functionality
    const searchContainer = input.closest(".relative");
    if (searchContainer) {
        const searchIcon = searchContainer.querySelector("svg");
        if (searchIcon) {
            searchIcon.style.cursor = "pointer";
            searchIcon.addEventListener("click", () =>
                performSearch(input.value)
            );
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
    if (event.key === "Enter") {
        event.preventDefault();
        const query = event.target.value.trim();
        performSearch(query);
    }

    // Handle Escape key to clear search
    if (event.key === "Escape") {
        event.target.value = "";
        performSearch("");
    }
}

function performSearch(query) {
    if (currentSearchQuery === query) {
        return; // Avoid duplicate searches
    }

    currentSearchQuery = query;

    // Reset pagination when searching
    if (window.currentPage !== undefined) {
        window.currentPage = 1;
    }

    // Show loading state
    showSearchLoading();

    // Get current section from URL
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get("section") || "all";

    // Build search URL
    const searchParams = new URLSearchParams();
    if (query) {
        searchParams.set("search", query);
    }
    if (section !== "all") {
        searchParams.set("section", section);
    }
    searchParams.set("page", "1");

    const searchUrl = `/dashboard?${searchParams.toString()}`;

    console.log("Search Request:", {
        query: query,
        section: section,
        url: searchUrl,
    });

    // Perform search request
    fetchWithErrorHandling(searchUrl, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((data) => {
            console.log("Search Response:", data);

            if (data.success) {
                // Update products grid
                updateProductsGrid(data.html);

                // Update pagination state
                if (window.currentPage !== undefined) {
                    window.currentPage = data.currentPage;
                }
                if (window.hasMore !== undefined) {
                    window.hasMore = data.hasMore;
                }

                console.log("Search completed, updated state:", {
                    currentPage: window.currentPage,
                    hasMore: window.hasMore,
                    total: data.total || "unknown",
                });

                // Update load more button visibility
                if (typeof updateLoadMoreButton === "function") {
                    updateLoadMoreButton();
                }

                // Update URL without page reload
                updateSearchUrl(query, section);

                // Show search results message
                showSearchResults(
                    query,
                    data.products ? data.products.length : 0
                );
            } else {
                throw new Error("Invalid search response");
            }
        })
        .catch((error) => {
            console.error("Search error:", error);
            showToast("Gagal melakukan pencarian. Silakan coba lagi.", "error");
        })
        .finally(() => {
            hideSearchLoading();
        });
}

function updateSearchUrl(query, section) {
    if (query) {
        const newUrl = new URL(window.location);
        newUrl.searchParams.set("search", query);
        if (section !== "all") {
            newUrl.searchParams.set("section", section);
        }
        newUrl.searchParams.delete("page");
        window.history.replaceState({}, "", newUrl);
    } else {
        // Clear search from URL
        const newUrl = new URL(window.location);
        newUrl.searchParams.delete("search");
        if (section === "all") {
            newUrl.searchParams.delete("section");
        }
        newUrl.searchParams.delete("page");
        window.history.replaceState({}, "", newUrl);
    }
}

function showSearchLoading() {
    const productsGrid = document.getElementById("products-grid");
    const loadingSpinner = document.getElementById("loading-spinner");

    if (productsGrid) {
        productsGrid.style.opacity = "0.5";
    }

    if (loadingSpinner) {
        loadingSpinner.classList.remove("hidden");
    }
}

function hideSearchLoading() {
    const productsGrid = document.getElementById("products-grid");
    const loadingSpinner = document.getElementById("loading-spinner");

    if (productsGrid) {
        productsGrid.style.opacity = "1";
    }

    if (loadingSpinner) {
        loadingSpinner.classList.add("hidden");
    }
}

function updateProductsGrid(html) {
    const productsGrid = document.getElementById("products-grid");
    if (productsGrid && html) {
        productsGrid.innerHTML = html;
    }
}

function showSearchResults(query, count) {
    if (query) {
        const message =
            count > 0
                ? `Ditemukan ${count} produk untuk "${query}"`
                : `Tidak ada produk ditemukan untuk "${query}"`;

        showToast(message, count > 0 ? "success" : "info");
    }
}

function initializeSearchHotkey() {
    document.addEventListener("keydown", function (event) {
        if ((event.metaKey || event.ctrlKey) && event.key === "k") {
            event.preventDefault();

            // Focus on search input
            const searchInput =
                document.querySelector('.max-w-lg input[type="text"]') ||
                document.querySelector('.md\\:hidden input[type="text"]');

            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
    });
}

function clearSearch() {
    currentSearchQuery = "";

    // Clear search inputs
    const searchInputs = document.querySelectorAll('input[type="text"]');
    searchInputs.forEach((input) => {
        if (
            input.placeholder &&
            input.placeholder.toLowerCase().includes("cari")
        ) {
            input.value = "";
        }
    });

    // Perform empty search to reset results
    performSearch("");
}

function getSearchQuery() {
    return currentSearchQuery;
}

function setSearchQuery(query) {
    currentSearchQuery = query;

    // Update search inputs
    const searchInputs = document.querySelectorAll('input[type="text"]');
    searchInputs.forEach((input) => {
        if (
            input.placeholder &&
            input.placeholder.toLowerCase().includes("cari")
        ) {
            input.value = query;
        }
    });
}

// Make functions available globally
window.initializeSearch = initializeSearch;
window.performSearch = performSearch;
window.clearSearch = clearSearch;
window.getSearchQuery = getSearchQuery;
window.setSearchQuery = setSearchQuery;
