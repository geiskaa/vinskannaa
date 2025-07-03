// Tab functionality
document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    tabButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const tabName = this.dataset.tab;

            // Remove active class from all buttons
            tabButtons.forEach((btn) => {
                btn.classList.remove(
                    "active",
                    "text-blue-600",
                    "border-blue-600"
                );
                btn.classList.add("text-gray-500", "border-transparent");
            });

            // Add active class to clicked button
            this.classList.add("active", "text-blue-600", "border-blue-600");
            this.classList.remove("text-gray-500", "border-transparent");

            // Hide all tab contents
            tabContents.forEach((content) => {
                content.classList.add("hidden");
            });

            // Show selected tab content
            document
                .getElementById(tabName + "-tab")
                .classList.remove("hidden");
        });
    });

    // Set initial active tab
    document
        .querySelector(".tab-button.active")
        .classList.add("text-blue-600", "border-blue-600");
    document
        .querySelector(".tab-button.active")
        .classList.remove("text-gray-500", "border-transparent");
});

// Toast notification function
function showToast(message, type = "success") {
    const toast = document.createElement("div");
    toast.className = `toast-notification p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full ${
        type === "success"
            ? "bg-green-500 text-white"
            : type === "error"
            ? "bg-red-500 text-white"
            : "bg-blue-500 text-white"
    }`;
    toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

    document.getElementById("toast-container").appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove("translate-x-full");
    }, 100);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.classList.add("translate-x-full");
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Order functions
function viewOrderDetail(orderId) {
    window.location.href = `/pesanan-saya/${orderId}`;
}

function cancelOrder(orderId) {
    if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) {
        fetch(`/pesanan-saya/${orderId}/cancel`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),

                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("Pesanan berhasil dibatalkan", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast("Gagal membatalkan pesanan", "error");
                }
            })
            .catch((error) => {
                showToast("Terjadi kesalahan", "error");
            });
    }
}

function rateOrder(orderId) {
    window.location.href = `/pesanan-saya/${orderId}/rate`;
}

// Wishlist functions
function removeFavorite(button, favoriteId) {
    if (confirm("Hapus dari wishlist?")) {
        // Ganti ikon dengan spinner
        const iconWrapper = button.querySelector(".icon-wrapper");
        iconWrapper.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        `;
        button.disabled = true;

        fetch(`/favorites/${favoriteId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),

                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("Produk dihapus dari wishlist", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast("Gagal menghapus dari wishlist", "error");
                    resetRemoveButton(button);
                }
            })
            .catch((error) => {
                showToast("Terjadi kesalahan", "error");
                resetRemoveButton(button);
            });
    }
}

function resetRemoveButton(button) {
    const iconWrapper = button.querySelector(".icon-wrapper");
    iconWrapper.innerHTML = `
        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
        </svg>
    `;
    button.disabled = false;
}

function addToCart(productId, price) {
    fetch(`/cart/add`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),

            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
            price: price,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Produk ditambahkan ke keranjang", "success");
            } else {
                showToast("Gagal menambahkan ke keranjang", "error");
            }
        })
        .catch((error) => {
            showToast("Terjadi kesalahan", "error");
        });
}

function viewProduct(productId) {
    window.location.href = `/products/${productId}`;
}
