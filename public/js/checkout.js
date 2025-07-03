document.addEventListener("DOMContentLoaded", function () {
    const shippingInputs = document.querySelectorAll(
        'input[name="shipping_method"]'
    );
    const shippingCostEl = document.getElementById("shipping-cost");
    const totalAmountEl = document.getElementById("total-amount");
    const checkoutBtn = document.getElementById("checkout-btn");
    const checkoutForm = document.getElementById("checkout-form");

    const subtotal = window.checkoutData.subtotal;
    const tax = window.checkoutData.tax;
    console.log("Subtotal:", subtotal);
    const shippingCosts = {
        regular: 15000,
        express: 25000,
        same_day: 35000,
    };

    // Update shipping cost when method changes
    shippingInputs.forEach((input) => {
        input.addEventListener("change", function () {
            const selectedShipping = this.value;
            const shippingCost = shippingCosts[selectedShipping];
            const newTotal = subtotal + tax + shippingCost;

            shippingCostEl.textContent =
                "Rp " + shippingCost.toLocaleString("id-ID");
            totalAmountEl.textContent =
                "Rp " + newTotal.toLocaleString("id-ID");
        });
    });

    // Form submission
    checkoutForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // Disable button and show loading
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = `
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                <span>Memproses...</span>
            </div>
        `;

        // Get form data
        const formData = new FormData(this);

        // Add selected shipping cost to form data
        const selectedShippingInput = document.querySelector(
            'input[name="shipping_method"]:checked'
        );
        if (!selectedShippingInput) {
            showToast("Silakan pilih metode pengiriman", "error");
            resetButton();
            return;
        }

        const selectedShipping = selectedShippingInput.value;
        const shippingCost = shippingCosts[selectedShipping];
        formData.append("shipping_cost", shippingCost);

        // Calculate total
        const total = subtotal + tax + shippingCost;
        formData.append("total_amount", total);

        // Submit form dengan proper error handling
        fetch(this.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    return response.json().then((err) => Promise.reject(err));
                }
                return response.json();
            })
            .then((data) => {
                console.log("Response data:", data);

                if (data.success && data.snap_token) {
                    showToast(
                        "Mengarahkan ke halaman pembayaran...",
                        "success"
                    );

                    // Use Midtrans Snap
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            console.log("Payment success:", result);
                            showToast("Pembayaran berhasil!", "success");
                            setTimeout(() => {
                                window.location.href =
                                    "/orders/" + data.order_id;
                            }, 1500);
                        },
                        onPending: function (result) {
                            console.log("Payment pending:", result);
                            showToast(
                                "Pembayaran pending, silakan selesaikan pembayaran.",
                                "warning"
                            );
                            setTimeout(() => {
                                window.location.href =
                                    "/orders/" + data.order_id;
                            }, 1500);
                        },
                        onError: function (result) {
                            console.log("Payment error:", result);
                            showToast(
                                "Pembayaran gagal, silakan coba lagi.",
                                "error"
                            );
                            resetButton();
                        },
                        onClose: function () {
                            console.log("Payment popup closed");
                            showToast("Pembayaran dibatalkan.", "warning");
                            resetButton();
                        },
                    });
                } else {
                    throw new Error(
                        data.message || "Tidak ada snap token yang diterima"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                let errorMessage = "Terjadi kesalahan saat memproses pesanan";

                if (error.message) {
                    errorMessage = error.message;
                } else if (error.errors) {
                    // Validation errors
                    const firstError = Object.values(error.errors)[0];
                    errorMessage = Array.isArray(firstError)
                        ? firstError[0]
                        : firstError;
                }

                showToast(errorMessage, "error");
                resetButton();
            });
    });

    function resetButton() {
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Lanjut ke Pembayaran</span>
        `;
    }

    function showToast(message, type = "success") {
        const toastContainer = document.getElementById("toast-container");
        const toast = document.createElement("div");
        toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

        let bgColor, icon;
        switch (type) {
            case "success":
                bgColor = "bg-green-500";
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
                break;
            case "warning":
                bgColor = "bg-yellow-500";
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>`;
                break;
            default:
                bgColor = "bg-red-500";
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
        }

        toast.innerHTML = `
            <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-64 max-w-sm">
                <div class="flex-shrink-0">${icon}</div>
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

        setTimeout(() => {
            toast.classList.remove("translate-x-full", "opacity-0");
            toast.classList.add("translate-x-0", "opacity-100");
        }, 100);

        setTimeout(() => {
            removeToast(toast);
        }, 4000);
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
            }, 300);
        }
    }

    // Make removeToast globally accessible
    window.removeToast = removeToast;
});
