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
function updateCartQuantity(cartId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCart(cartId);
        return;
    }

    // Get cart item elements
    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
    if (!cartItem) return;

    const quantityDisplay = cartItem.querySelector(".quantity-display");
    const subtotalElement = cartItem.querySelector(".item-subtotal");
    const minusButton = cartItem.querySelector(
        'button[onclick*="' + (newQuantity - 1) + '"]'
    );
    const plusButton = cartItem.querySelector(
        'button[onclick*="' + (newQuantity + 1) + '"]'
    );

    // Store original values for rollback if needed
    const originalQuantity = parseInt(quantityDisplay.textContent);
    const pricePerItem = parseInt(cartItem.dataset.price) || 0;
    const originalSubtotal = subtotalElement.textContent;

    // Update UI instantly (optimistic update)
    updateCartItemUI(cartItem, newQuantity, pricePerItem);

    // Disable buttons temporarily
    const allButtons = cartItem.querySelectorAll("button");
    allButtons.forEach((btn) => {
        btn.disabled = true;
        btn.classList.add("opacity-50", "cursor-not-allowed");
    });

    // Add loading animation
    quantityDisplay.classList.add("animate-pulse");

    // Send request to server
    fetchWithErrorHandling(`/cart/${cartId}/quantity`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            quantity: newQuantity,
        }),
    })
        .then((data) => {
            if (data.success) {
                // Update global cart counter
                updateCartCounter(data.cart_count);

                // Update order summary
                updateOrderSummary();

                // Show success toast
                showToast("Kuantitas berhasil diperbarui", "success");

                // Update button onclick handlers with new quantity
                updateButtonHandlers(cartItem, cartId, newQuantity);

                // Add success animation
                addSuccessAnimation(quantityDisplay);
            } else {
                // Rollback UI changes
                rollbackCartItemUI(
                    cartItem,
                    originalQuantity,
                    originalSubtotal
                );
                showToast(
                    data.message || "Gagal memperbarui kuantitas",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            // Rollback UI changes
            rollbackCartItemUI(cartItem, originalQuantity, originalSubtotal);
            showToast("Terjadi kesalahan jaringan", "error");
        })
        .finally(() => {
            // Re-enable buttons
            allButtons.forEach((btn) => {
                btn.disabled = false;
                btn.classList.remove("opacity-50", "cursor-not-allowed");
            });

            // Remove loading animation
            quantityDisplay.classList.remove("animate-pulse");
        });
}

// Update cart item UI elements
function updateCartItemUI(cartItem, quantity, pricePerItem) {
    const quantityDisplay = cartItem.querySelector(".quantity-display");
    const subtotalElement = cartItem.querySelector(".item-subtotal");

    // Update quantity display
    quantityDisplay.textContent = quantity;

    // Update subtotal
    const newSubtotal = quantity * pricePerItem;
    subtotalElement.textContent = formatCurrency(newSubtotal);

    // Update button states
    const minusButton = cartItem.querySelector(
        'button[onclick*="' + (quantity - 1) + '"]'
    );
    const plusButton = cartItem.querySelector(
        'button[onclick*="' + (quantity + 1) + '"]'
    );

    // Update minus button state
    if (quantity <= 1) {
        if (minusButton) {
            minusButton.classList.add("opacity-50", "cursor-not-allowed");
            minusButton.disabled = true;
        }
    } else {
        if (minusButton) {
            minusButton.classList.remove("opacity-50", "cursor-not-allowed");
            minusButton.disabled = false;
        }
    }

    // Check stock for plus button
    const maxStock = parseInt(cartItem.dataset.maxStock) || 999;
    if (quantity >= maxStock) {
        if (plusButton) {
            plusButton.classList.add("opacity-50", "cursor-not-allowed");
            plusButton.disabled = true;
        }
    } else {
        if (plusButton) {
            plusButton.classList.remove("opacity-50", "cursor-not-allowed");
            plusButton.disabled = false;
        }
    }
}

// Rollback UI changes if server request fails
function rollbackCartItemUI(cartItem, originalQuantity, originalSubtotal) {
    const quantityDisplay = cartItem.querySelector(".quantity-display");
    const subtotalElement = cartItem.querySelector(".item-subtotal");

    quantityDisplay.textContent = originalQuantity;
    subtotalElement.textContent = originalSubtotal;

    // Add error animation
    addErrorAnimation(quantityDisplay);
}

// Update button onclick handlers with new quantity values
function updateButtonHandlers(cartItem, cartId, currentQuantity) {
    const minusButton = cartItem.querySelector(
        'button[onclick*="updateCartQuantity"]'
    );
    const plusButton = cartItem.querySelectorAll(
        'button[onclick*="updateCartQuantity"]'
    )[1];

    if (minusButton) {
        minusButton.setAttribute(
            "onclick",
            `updateCartQuantity(${cartId}, ${currentQuantity - 1})`
        );
    }

    if (plusButton) {
        plusButton.setAttribute(
            "onclick",
            `updateCartQuantity(${cartId}, ${currentQuantity + 1})`
        );
    }
}

// Update order summary in real-time
function updateOrderSummary() {
    const cartItems = document.querySelectorAll(".cart-item");
    let totalItems = 0;
    let totalPrice = 0;

    cartItems.forEach((item) => {
        const quantity = parseInt(
            item.querySelector(".quantity-display").textContent
        );
        const price = parseInt(item.dataset.price) || 0;

        totalItems += quantity;
        totalPrice += quantity * price;
    });

    // Update total items
    const totalItemsElements = document.querySelectorAll(".total-items");
    totalItemsElements.forEach((element) => {
        element.textContent = `${totalItems} item${
            totalItems !== 1 ? "s" : ""
        }`;
    });

    // Update total price
    const totalPriceElements = document.querySelectorAll(".total-price");
    totalPriceElements.forEach((element) => {
        element.textContent = formatCurrency(totalPrice);
    });
}

// Animation helpers
function addSuccessAnimation(element) {
    element.classList.add("animate-bounce");
    setTimeout(() => {
        element.classList.remove("animate-bounce");
    }, 1000);
}

function addErrorAnimation(element) {
    element.classList.add("animate-shake");
    setTimeout(() => {
        element.classList.remove("animate-shake");
    }, 500);
}

// Utility function to format currency
function formatCurrency(amount) {
    return `Rp ${new Intl.NumberFormat("id-ID").format(amount)}`;
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
        const cartItems = document.querySelectorAll(".cart-item");

        // Add clearing animation to all items
        cartItems.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add("animate-pulse", "opacity-50");
            }, index * 100);
        });

        fetchWithErrorHandling("/cart", {
            method: "DELETE",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Keranjang berhasil dikosongkan", "success");
                    updateCartCounter(0);

                    // Animate all items out
                    cartItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.transform = "translateY(-20px)";
                            item.style.opacity = "0";
                            item.style.transition = "all 0.3s ease-out";
                        }, index * 50);
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // Remove animations if failed
                    cartItems.forEach((item) => {
                        item.classList.remove("animate-pulse", "opacity-50");
                    });
                    showToast(
                        data.message || "Gagal mengosongkan keranjang",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                cartItems.forEach((item) => {
                    item.classList.remove("animate-pulse", "opacity-50");
                });
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
