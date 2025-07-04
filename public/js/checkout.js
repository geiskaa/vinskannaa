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

                            // Call backend to create order
                            handlePaymentCallback("/handle-payment-success", {
                                order_id: result.order_id,
                                transaction_status: result.transaction_status,
                                payment_type: result.payment_type,
                            })
                                .then(() => {
                                    showToast(
                                        "Pembayaran berhasil!",
                                        "success"
                                    );
                                    setTimeout(() => {
                                        window.location.href = "/pesanan-saya";
                                    }, 1500);
                                })
                                .catch((error) => {
                                    console.error(
                                        "Error handling payment success:",
                                        error
                                    );
                                    showToast(
                                        "Pembayaran berhasil, tapi terjadi kesalahan sistem",
                                        "warning"
                                    );
                                });
                        },
                        onPending: function (result) {
                            // ⛔️ Dianggap batal/gagal
                            console.log(
                                "Payment pending (dianggap cancel):",
                                result
                            );
                            handleFailedOrClosed(data.order_id, "cancelled");
                        },

                        onError: function (result) {
                            // ❌ Gagal bayar
                            console.log("Payment error:", result);
                            handleFailedOrClosed(
                                result.order_id,
                                result.transaction_status || "failed"
                            );
                        },

                        onClose: function () {
                            // ❌ Popup ditutup user
                            console.log("Payment popup closed");
                            handleFailedOrClosed(data.order_id, "cancelled");
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

    function handleFailedOrClosed(orderId, status) {
        handlePaymentCallback("/handle-payment-failed", {
            order_id: orderId,
            transaction_status: status,
        })
            .then(() => {
                showToast(
                    "Pembayaran dibatalkan atau belum selesai.",
                    "warning"
                );
                resetButton();
            })
            .catch((error) => {
                console.error("Error handling failed/closed:", error);
                showToast(
                    "Terjadi kesalahan saat membatalkan pembayaran",
                    "error"
                );
                resetButton();
            });
    }

    // Function to handle payment callbacks
    function handlePaymentCallback(url, data) {
        return fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((err) => Promise.reject(err));
                }
                return response.json();
            })
            .then((result) => {
                if (!result.success) {
                    throw new Error(result.message || "Unknown error");
                }
                return result;
            });
    }

    function resetButton() {
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span>Lanjut ke Pembayaran</span>
        `;
    }
});
