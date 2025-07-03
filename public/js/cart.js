function updateQuantity(cartId, newQuantity) {
    if (newQuantity < 1) return;

    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
    const quantityDisplay = cartItem.querySelector(".quantity-display");
    const subtotalDisplay = cartItem.querySelector(".item-subtotal");

    // Add loading state
    cartItem.style.opacity = "0.7";
    cartItem.style.pointerEvents = "none";

    fetch(`/cart/${cartId}/quantity`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            quantity: newQuantity,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Update quantity display with animation
                quantityDisplay.style.transform = "scale(1.2)";
                quantityDisplay.textContent = newQuantity;
                setTimeout(() => {
                    quantityDisplay.style.transform = "scale(1)";
                }, 200);

                // Update subtotal
                const price = parseFloat(
                    cartItem
                        .querySelector("p")
                        .textContent.replace(/[^\d]/g, "")
                );
                const newSubtotal = newQuantity * price;
                subtotalDisplay.textContent = `Rp ${newSubtotal.toLocaleString(
                    "id-ID"
                )}`;

                // Update totals
                updateTotals();

                // Update cart counter in navbar
                updateCartCounter(data.cart_count);

                showToast(data.message, "success");
            } else {
                showToast(data.message, "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Terjadi kesalahan", "error");
        })
        .finally(() => {
            // Remove loading state
            cartItem.style.opacity = "1";
            cartItem.style.pointerEvents = "auto";
        });
}

function removeItem(cartId) {
    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);

    if (confirm("Yakin ingin menghapus produk ini dari keranjang?")) {
        fetch(`/cart/${cartId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Enhanced removal animation
                    cartItem.style.transition =
                        "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                    cartItem.style.opacity = "0";
                    cartItem.style.transform = "translateX(-100%) scale(0.8)";
                    cartItem.style.maxHeight = "0";
                    cartItem.style.marginBottom = "0";
                    cartItem.style.paddingTop = "0";
                    cartItem.style.paddingBottom = "0";

                    setTimeout(() => {
                        cartItem.remove();
                        updateTotals();

                        // Check if cart is now empty
                        if (
                            document.querySelectorAll(".cart-item").length === 0
                        ) {
                            location.reload();
                        }
                    }, 500);

                    // Update cart counter in navbar
                    updateCartCounter(data.cart_count);

                    showToast(data.message, "success");
                } else {
                    showToast(data.message, "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan", "error");
            });
    }
}

function clearCart() {
    if (confirm("Yakin ingin mengosongkan seluruh keranjang?")) {
        fetch("/cart", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Add fade out animation before reload
                    document.body.style.transition = "opacity 0.3s ease";
                    document.body.style.opacity = "0";
                    setTimeout(() => {
                        location.reload();
                    }, 300);
                } else {
                    showToast(data.message, "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Terjadi kesalahan", "error");
            });
    }
}

function updateTotals() {
    const cartItems = document.querySelectorAll(".cart-item");
    let totalItems = 0;
    let totalPrice = 0;

    cartItems.forEach((item) => {
        const quantity = parseInt(
            item.querySelector(".quantity-display").textContent
        );
        const price = parseFloat(
            item
                .querySelector(".item-subtotal")
                .textContent.replace(/[^\d]/g, "")
        );

        totalItems += quantity;
        totalPrice += price;
    });

    // Update displays with animation
    const totalItemsEl = document.querySelector(".total-items");
    const totalPriceEls = document.querySelectorAll(".total-price");

    totalItemsEl.style.transform = "scale(1.1)";
    totalItemsEl.textContent = `${totalItems} item`;
    setTimeout(() => {
        totalItemsEl.style.transform = "scale(1)";
    }, 200);

    totalPriceEls.forEach((el) => {
        el.style.transform = "scale(1.1)";
        el.textContent = `Rp ${totalPrice.toLocaleString("id-ID")}`;
        setTimeout(() => {
            el.style.transform = "scale(1)";
        }, 200);
    });
}

function updateCartCounter(count) {
    const cartCounter = document.getElementById("cart-counter");
    if (cartCounter) {
        cartCounter.style.transform = "scale(1.3)";
        cartCounter.textContent = count;
        if (count > 0) {
            cartCounter.classList.remove("hidden");
        } else {
            cartCounter.classList.add("hidden");
        }
        setTimeout(() => {
            cartCounter.style.transform = "scale(1)";
        }, 200);
    }
}

function showToast(message, type = "success") {
    const toastContainer = document.getElementById("toast-container");

    const toast = document.createElement("div");
    toast.className = `transform transition-all duration-500 ease-out translate-x-full opacity-0`;

    const bgGradient =
        type === "success"
            ? "bg-gradient-to-r from-green-500 to-emerald-600"
            : "bg-gradient-to-r from-red-500 to-pink-600";

    const icon =
        type === "success"
            ? `<div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>`
            : `<div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>`;

    toast.innerHTML = `
                <div class="${bgGradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-4 min-w-80 max-w-md backdrop-blur-sm border border-white/20">
                    ${icon}
                    <div class="flex-1">
                        <p class="font-semibold text-sm">${
                            type === "success" ? "Berhasil!" : "Oops!"
                        }</p>
                        <p class="text-sm opacity-90">${message}</p>
                    </div>
                    <button onclick="removeToast(this)" class="flex-shrink-0 ml-2 text-white/80 hover:text-white transition-colors p-1 hover:bg-white/20 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

    toastContainer.appendChild(toast);

    // Trigger entrance animation
    setTimeout(() => {
        toast.classList.remove("translate-x-full", "opacity-0");
        toast.classList.add("translate-x-0", "opacity-100");
    }, 100);

    // Auto remove after 5 seconds
    setTimeout(() => {
        removeToast(toast);
    }, 5000);
}

function removeToast(element) {
    const toast = element.closest(".transform");
    if (toast) {
        toast.classList.remove("translate-x-0", "opacity-100");
        toast.classList.add("translate-x-full", "opacity-0");

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }
}

// Add smooth scroll behavior and entrance animations
document.addEventListener("DOMContentLoaded", function () {
    // Add entrance animation to cart items
    const cartItems = document.querySelectorAll(".cart-item");
    cartItems.forEach((item, index) => {
        item.style.opacity = "0";
        item.style.transform = "translateY(20px)";
        setTimeout(() => {
            item.style.transition = "all 0.6s cubic-bezier(0.4, 0, 0.2, 1)";
            item.style.opacity = "1";
            item.style.transform = "translateY(0)";
        }, index * 100);
    });

    // Add floating animation to summary card
    const summaryCard = document.querySelector(".sticky");
    if (summaryCard) {
        summaryCard.style.animation = "float 6s ease-in-out infinite";
    }

    // Add pulse animation to checkout button
    const checkoutBtn = summaryCard?.querySelector("button");
    if (checkoutBtn) {
        setInterval(() => {
            checkoutBtn.style.animation = "pulse 2s ease-in-out";
            setTimeout(() => {
                checkoutBtn.style.animation = "";
            }, 2000);
        }, 10000);
    }
});

// Add custom CSS animations
const style = document.createElement("style");
style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-5px); }
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.02); }
            }
            
            .cart-item {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .cart-item:hover {
                transform: translateY(-2px);
            }
            
            /* Smooth transitions for all interactive elements */
            button, .quantity-display, .total-items, .total-price {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Loading shimmer effect */
            .loading {
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: shimmer 1.5s infinite;
            }
            
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
        `;
document.head.appendChild(style);
