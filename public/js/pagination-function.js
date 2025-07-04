/**
 * Pagination functionality for dashboard
 */

let currentPage;
let hasMore;
let isLoading;
document.addEventListener("DOMContentLoaded", function () {
    initializePagination();
});

function initializePagination() {
    const meta = document.getElementById("dashboard-meta");
    console.log("Initializing pagination with meta:", meta);
    if (!meta) return;

    currentPage = parseInt(meta.dataset.currentPage);
    hasMore = meta.dataset.hasMore === "true";
    isLoading = false;

    // Initialize load more button
    const loadMoreBtn = document.getElementById("load-more-btn");
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", loadMoreProducts);
    }

    // Update initial button state
    updateLoadMoreButton();
}
function loadMoreProducts() {
    if (isLoading || !hasMore) return;

    isLoading = true;
    const loadMoreBtn = document.getElementById("load-more-btn");
    const loadingSpinner = document.getElementById("loading-spinner");
    const productsGrid = document.getElementById("products-grid");

    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get("section") || "all";
    const search = urlParams.get("search") || "";

    console.log("Load More Request:", {
        currentPage: currentPage,
        nextPage: currentPage + 1,
        section: section,
        search: search,
        hasMore: hasMore,
    });

    // Show loading state
    setLoadingState(loadMoreBtn, true, "Memuat...");
    if (loadingSpinner) {
        loadingSpinner.classList.remove("hidden");
    }

    // Build URL parameters
    const params = new URLSearchParams();
    params.set("page", currentPage + 1);

    if (section !== "all") {
        params.set("section", section);
    }
    if (search) {
        params.set("search", search);
    }

    console.log("Fetch URL:", `/dashboard?${params.toString()}`);

    // Fetch with all parameters
    fetchWithErrorHandling(`/dashboard?${params.toString()}`, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
    })
        .then((data) => {
            console.log("Load More Response:", data);

            if (data.success && data.html) {
                const tempDiv = document.createElement("div");
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

                console.log("Updated state:", {
                    currentPage: currentPage,
                    hasMore: hasMore,
                    addedProducts: addedCount,
                    totalProducts: data.total || "unknown",
                });

                // Update load more button visibility
                updateLoadMoreButton();

                const message = search
                    ? `${addedCount} produk lagi dimuat untuk "${search}"`
                    : `${addedCount} produk baru dimuat`;

                showToast(message, "success");
            } else {
                console.error("Invalid response data:", data);
                throw new Error("Invalid response data");
            }
        })
        .catch((error) => {
            console.error("Error loading more products:", error);
            showToast("Gagal memuat produk. Silakan coba lagi.", "error");
        })
        .finally(() => {
            isLoading = false;
            if (loadingSpinner) {
                loadingSpinner.classList.add("hidden");
            }
            setLoadingState(loadMoreBtn, false);
        });
}

function updateLoadMoreButton() {
    const loadMoreContainer = document.getElementById("load-more-container");
    const noMoreProducts = document.getElementById("no-more-products");

    console.log("Updating load more button, hasMore:", hasMore);

    if (hasMore) {
        if (loadMoreContainer) loadMoreContainer.classList.remove("hidden");
        if (noMoreProducts) noMoreProducts.classList.add("hidden");
    } else {
        if (loadMoreContainer) loadMoreContainer.classList.add("hidden");
        if (noMoreProducts) noMoreProducts.classList.remove("hidden");
    }
}

function resetPagination() {
    currentPage = 1;
    hasMore = false;
    isLoading = false;
    updateLoadMoreButton();
}

function setPaginationState(page, hasMoreItems) {
    currentPage = page;
    hasMore = hasMoreItems;
    updateLoadMoreButton();
}

function getCurrentPage() {
    return currentPage;
}

function getHasMore() {
    return hasMore;
}

function getIsLoading() {
    return isLoading;
}

// Infinite scroll functionality (optional)
function initializeInfiniteScroll(threshold = 100) {
    let infiniteScrollEnabled = false;

    function enableInfiniteScroll() {
        infiniteScrollEnabled = true;
        window.addEventListener("scroll", handleInfiniteScroll);
    }

    function disableInfiniteScroll() {
        infiniteScrollEnabled = false;
        window.removeEventListener("scroll", handleInfiniteScroll);
    }

    function handleInfiniteScroll() {
        if (!infiniteScrollEnabled || isLoading || !hasMore) return;

        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        if (scrollTop + windowHeight >= documentHeight - threshold) {
            loadMoreProducts();
        }
    }

    return {
        enable: enableInfiniteScroll,
        disable: disableInfiniteScroll,
        isEnabled: () => infiniteScrollEnabled,
    };
}

// Make functions available globally
window.currentPage = currentPage;
window.hasMore = hasMore;
window.isLoading = isLoading;
window.initializePagination = initializePagination;
window.loadMoreProducts = loadMoreProducts;
window.updateLoadMoreButton = updateLoadMoreButton;
window.resetPagination = resetPagination;
window.setPaginationState = setPaginationState;
window.getCurrentPage = getCurrentPage;
window.getHasMore = getHasMore;
window.getIsLoading = getIsLoading;
window.initializeInfiniteScroll = initializeInfiniteScroll;
