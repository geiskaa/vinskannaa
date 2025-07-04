/**
 * Cart functionality functions
 */

// Toggle cart item
function toggleCart(productId, eventType = "icon") {
    const btn = document.querySelector(
        `[data-product-id="${productId}"].cart-btn`
    );
    if (!btn) return;

    const icon = btn.querySelector("svg");
    const currentInCart = btn.getAttribute("data-in-cart") === "true";

    // Check if button is disabled (out of stock)
    if (btn.disabled) {
        showToast("Produk sedang habis stok", "error");
        return;
    }

    // Disable button temporarily
    btn.disabled = true;
    btn.classList.add("animate-pulse");

    fetchWithErrorHandling(`/cart/${productId}/toggle`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            event: eventType, // "icon" atau "detail"
        }),
    })
        .then((data) => {
            if (data.success) {
                const newInCart = data.in_cart;
                btn.setAttribute("data-in-cart", newInCart);

                // Update icon appearance
                if (newInCart) {
                    icon.classList.add("text-green-600", "scale-110");
                    icon.classList.remove("text-gray-400");
                    addShakeAnimation(icon);
                } else {
                    icon.classList.remove("text-green-600", "scale-110");
                    icon.classList.add("text-gray-400");
                }

                updateCartCounter(data.cart_count);
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
        });
}

// Add to cart
function addToCart(productId, price, quantity = 1) {
    const payload = {
        product_id: productId,
        quantity: quantity,
        price: price,
    };

    fetchWithErrorHandling("/cart/add", {
        method: "POST",
        body: JSON.stringify(payload),
    })
        .then((data) => {
            if (data.success) {
                showToast("Produk ditambahkan ke keranjang", "success");
                updateCartCounter(data.cart_count);
            } else {
                showToast(
                    data.message || "Gagal menambahkan ke keranjang",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan jaringan", "error");
        });
}

// Remove from cart
function removeFromCart(productId) {
    confirmAction("Hapus produk dari keranjang?", () => {
        fetchWithErrorHandling(`/cart/${productId}`, {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Produk dihapus dari keranjang", "success");
                    updateCartCounter(data.cart_count);

                    // Remove cart item from DOM if exists
                    const cartItem = document.querySelector(
                        `[data-cart-item="${productId}"]`
                    );
                    if (cartItem) {
                        cartItem.remove();
                    }
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showToast(
                        data.message || "Gagal menghapus dari keranjang",
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

// Update cart quantity
function updateCartQuantity(productId, quantity) {
    if (quantity <= 0) {
        removeFromCart(productId);
        return;
    }

    const payload = {
        quantity: quantity,
    };

    fetchWithErrorHandling(`/cart/${productId}/quantity`, {
        method: "PATCH", // atau POST/DELETE
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(payload),
    })
        .then((data) => {
            if (data.success) {
                showToast("Kuantitas berhasil diperbarui", "success");
                updateCartCounter(data.cart_count);

                // Update cart item subtotal if exists
                const cartItem = document.querySelector(
                    `[data-cart-item="${productId}"]`
                );
                if (cartItem) {
                    const subtotalElement =
                        cartItem.querySelector(".cart-subtotal");
                    if (subtotalElement) {
                        subtotalElement.textContent = formatCurrency(
                            data.subtotal
                        );
                    }
                }
            } else {
                showToast(
                    data.message || "Gagal memperbarui kuantitas",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan jaringan", "error");
        });
}

// Update cart counter in navbar
function updateCartCounter(count) {
    const cartCounter = document.getElementById("cart-counter");
    if (cartCounter) {
        cartCounter.textContent = count;
        if (count > 0) {
            cartCounter.classList.remove("hidden");
            addBounceAnimation(cartCounter);
        } else {
            cartCounter.classList.add("hidden");
        }
    }
}

// Clear entire cart
function clearCart() {
    confirmAction("Kosongkan semua produk dari keranjang?", () => {
        fetchWithErrorHandling("/cart", {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Keranjang berhasil dikosongkan", "success");
                    updateCartCounter(0);

                    // Clear cart items from DOM
                    const cartItems =
                        document.querySelectorAll("[data-cart-item]");
                    cartItems.forEach((item) => item.remove());
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(
                        data.message || "Gagal mengosongkan keranjang",
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
// Initialize cart functionality
function initializeCart() {
    // Initialize quantity inputs
    const quantityInputs = document.querySelectorAll(".cart-quantity-input");
    quantityInputs.forEach((input) => {
        input.addEventListener("change", (e) => {
            const productId = e.target.dataset.productId;
            const quantity = parseInt(e.target.value);
            updateCartQuantity(productId, quantity);
        });
    });

    // Initialize quantity buttons
    const quantityButtons = document.querySelectorAll(".cart-quantity-btn");
    quantityButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            const productId = e.target.dataset.productId;
            const action = e.target.dataset.action;
            const input = document.querySelector(
                `[data-product-id="${productId}"].cart-quantity-input`
            );

            if (input) {
                let quantity = parseInt(input.value);
                if (action === "increase") {
                    quantity += 1;
                } else if (action === "decrease") {
                    quantity -= 1;
                }

                input.value = quantity;
                updateCartQuantity(productId, quantity);
            }
        });
    });

    // Initialize coupon form
    const couponForm = document.getElementById("coupon-form");
    if (couponForm) {
        couponForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const couponInput = couponForm.querySelector(
                "input[name='coupon_code']"
            );
            if (couponInput) {
                applyCoupon(couponInput.value);
            }
        });
    }
}

// Auto-initialize when DOM is ready
document.addEventListener("DOMContentLoaded", initializeCart);

// Make functions available globally
window.toggleCart = toggleCart;
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;
window.updateCartQuantity = updateCartQuantity;
window.updateCartCounter = updateCartCounter;
window.clearCart = clearCart;
window.initializeCart = initializeCart;
