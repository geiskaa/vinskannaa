/**
 * Favorites/Wishlist functionality functions
 */

// Toggle favorite status
let toggleLock = {}; // di global scope

function toggleFavorite(productId) {
    if (toggleLock[productId]) return; // prevent double click
    toggleLock[productId] = true;

    const btn = document.querySelector(
        `[data-product-id="${productId}"].favorite-btn`
    );
    const icon = btn.querySelector("svg");
    const currentIsFavorited = btn.getAttribute("data-is-favorited") === "true";

    btn.disabled = true;
    btn.classList.add("animate-pulse");

    fetchWithErrorHandling(`/favorites/${productId}/toggle`, {
        method: "POST",
    })
        .then((data) => {
            if (data.success) {
                const newIsFavorited = data.is_favorited;
                btn.setAttribute("data-is-favorited", newIsFavorited);

                if (newIsFavorited) {
                    icon.classList.add(
                        "text-red-500",
                        "fill-current",
                        "scale-110"
                    );
                    icon.classList.remove("text-gray-400");
                    icon.setAttribute("fill", "currentColor");
                    addBounceAnimation(icon);
                } else {
                    icon.classList.remove(
                        "text-red-500",
                        "fill-current",
                        "scale-110"
                    );
                    icon.classList.add("text-gray-400");
                    icon.setAttribute("fill", "none");
                }

                showToast(data.message, "success");
            } else {
                showToast(data.message || "Terjadi kesalahan", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan jaringan", "error");
        })
        .finally(() => {
            btn.disabled = false;
            btn.classList.remove("animate-pulse");
            toggleLock[productId] = false; // release lock
        });
}

// Add to favorites
function addToFavorites(productId) {
    fetchWithErrorHandling(`/favorites/${productId}/add`, {
        method: "POST",
    })
        .then((data) => {
            if (data.success) {
                showToast("Produk ditambahkan ke wishlist", "success");

                // Update favorite button state if exists
                const btn = document.querySelector(
                    `[data-product-id="${productId}"].favorite-btn`
                );
                if (btn) {
                    btn.setAttribute("data-is-favorited", "true");
                    const icon = btn.querySelector("svg");
                    if (icon) {
                        icon.classList.add("text-red-500", "fill-current");
                        icon.classList.remove("text-gray-400");
                        icon.setAttribute("fill", "currentColor");
                        addBounceAnimation(icon);
                    }
                }
            } else {
                showToast(
                    data.message || "Gagal menambahkan ke wishlist",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan jaringan", "error");
        });
}

// Remove from favorites
function removeFromFavorites(productId) {
    confirmAction("Hapus dari wishlist?", () => {
        fetchWithErrorHandling(`/favorites/${productId}/remove`, {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Produk dihapus dari wishlist", "success");

                    // Update favorite button state if exists
                    const btn = document.querySelector(
                        `[data-product-id="${productId}"].favorite-btn`
                    );
                    if (btn) {
                        btn.setAttribute("data-is-favorited", "false");
                        const icon = btn.querySelector("svg");
                        if (icon) {
                            icon.classList.remove(
                                "text-red-500",
                                "fill-current"
                            );
                            icon.classList.add("text-gray-400");
                            icon.setAttribute("fill", "none");
                        }
                    }

                    // Remove from favorites page if exists
                    const favoriteItem = document.querySelector(
                        `[data-favorite-item="${productId}"]`
                    );
                    if (favoriteItem) {
                        favoriteItem.remove();
                    }
                } else {
                    showToast(
                        data.message || "Gagal menghapus dari wishlist",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan jaringan", "error");
            });
    });
}

// Remove favorite with button reference (for favorites page)
function removeFavorite(button, favoriteId) {
    confirmAction("Hapus dari wishlist?", () => {
        // Show loading state
        const iconWrapper = button.querySelector(".icon-wrapper");
        const originalIcon = iconWrapper.innerHTML;

        iconWrapper.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        `;
        button.disabled = true;

        fetchWithErrorHandling(`/favorites/${favoriteId}`, {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Produk dihapus dari wishlist", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(
                        data.message || "Gagal menghapus dari wishlist",
                        "error"
                    );
                    resetRemoveButton(button, originalIcon);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan jaringan", "error");
                resetRemoveButton(button, originalIcon);
            });
    });
}

// Reset remove button to original state
function resetRemoveButton(button, originalIcon = null) {
    const iconWrapper = button.querySelector(".icon-wrapper");
    iconWrapper.innerHTML =
        originalIcon ||
        `
        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
        </svg>
    `;
    button.disabled = false;
}

// Get favorites list
function getFavoritesList() {
    return fetchWithErrorHandling("/favorites")
        .then((data) => {
            if (data.success) {
                return data.favorites;
            } else {
                throw new Error(
                    data.message || "Gagal mendapatkan daftar wishlist"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan saat memuat wishlist", "error");
            throw error;
        });
}

// Clear all favorites
function clearFavorites() {
    confirmAction("Hapus semua produk dari wishlist?", () => {
        fetchWithErrorHandling("/favorites/clear", {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Wishlist berhasil dikosongkan", "success");

                    // Remove all favorite items from DOM
                    const favoriteItems = document.querySelectorAll(
                        "[data-favorite-item]"
                    );
                    favoriteItems.forEach((item) => item.remove());

                    // Update all favorite buttons to unfavorited state
                    const favoriteButtons =
                        document.querySelectorAll(".favorite-btn");
                    favoriteButtons.forEach((btn) => {
                        btn.setAttribute("data-is-favorited", "false");
                        const icon = btn.querySelector("svg");
                        if (icon) {
                            icon.classList.remove(
                                "text-red-500",
                                "fill-current"
                            );
                            icon.classList.add("text-gray-400");
                            icon.setAttribute("fill", "none");
                        }
                    });
                } else {
                    showToast(
                        data.message || "Gagal mengosongkan wishlist",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan jaringan", "error");
            });
    });
}

// Check if product is in favorites
function isProductInFavorites(productId) {
    return fetchWithErrorHandling(`/favorites/${productId}/check`)
        .then((data) => {
            if (data.success) {
                return data.is_favorited;
            } else {
                return false;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            return false;
        });
}

// Move from favorites to cart
function moveToCart(productId, price) {
    fetchWithErrorHandling(`/favorites/${productId}/move-to-cart`, {
        method: "POST",
        body: JSON.stringify({
            price: price,
        }),
    })
        .then((data) => {
            if (data.success) {
                showToast("Produk dipindahkan ke keranjang", "success");

                // Update cart counter
                if (window.updateCartCounter) {
                    updateCartCounter(data.cart_count);
                }

                // Remove from favorites list
                const favoriteItem = document.querySelector(
                    `[data-favorite-item="${productId}"]`
                );
                if (favoriteItem) {
                    favoriteItem.remove();
                }
            } else {
                showToast(
                    data.message || "Gagal memindahkan ke keranjang",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan jaringan", "error");
        });
}

// Initialize favorites functionality
function initializeFavorites() {
    // Initialize favorite buttons
    const favoriteButtons = document.querySelectorAll(".favorite-btn");
    favoriteButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            const productId = button.dataset.productId;
            if (productId) {
                toggleFavorite(productId);
            }
        });
    });

    // Initialize remove buttons on favorites page
    const removeButtons = document.querySelectorAll(".remove-favorite-btn");
    removeButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            const favoriteId = button.dataset.favoriteId;
            if (favoriteId) {
                removeFavorite(button, favoriteId);
            }
        });
    });
}

// Auto-initialize when DOM is ready
document.addEventListener("DOMContentLoaded", initializeFavorites);

// Make functions available globally
window.toggleFavorite = toggleFavorite;
window.addToFavorites = addToFavorites;
window.removeFromFavorites = removeFromFavorites;
window.removeFavorite = removeFavorite;
window.resetRemoveButton = resetRemoveButton;
window.getFavoritesList = getFavoritesList;
window.clearFavorites = clearFavorites;
window.isProductInFavorites = isProductInFavorites;
window.moveToCart = moveToCart;
window.initializeFavorites = initializeFavorites;
