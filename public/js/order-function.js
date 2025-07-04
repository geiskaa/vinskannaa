/**
 * Order/Pesanan related functions
 * Requires: utils.js
 */

// Cancel order function
function cancelOrder(orderId) {
    const message = "Apakah Anda yakin ingin membatalkan pesanan ini?";

    confirmAction(message, () => {
        fetchWithErrorHandling(`/pesanan-saya/${orderId}/cancel`, {
            method: "POST",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Pesanan berhasil dibatalkan", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(
                        data.message || "Gagal membatalkan pesanan",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Cancel order error:", error);
                showToast(
                    "Terjadi kesalahan saat membatalkan pesanan",
                    "error"
                );
            });
    });
}
function completeOrder(orderId) {
    const message = "Apakah Anda yakin ingin membatalkan pesanan ini?";

    confirmAction(message, () => {
        fetchWithErrorHandling(`/pesanan-saya/${orderId}/cancel`, {
            method: "POST",
        })
            .then((data) => {
                if (data.success) {
                    showToast("Pesanan berhasil dibatalkan", "success");
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showToast(
                        data.message || "Gagal membatalkan pesanan",
                        "error"
                    );
                }
            })
            .catch((error) => {
                console.error("Cancel order error:", error);
                showToast(
                    "Terjadi kesalahan saat membatalkan pesanan",
                    "error"
                );
            });
    });
}

// View order detail
function viewOrderDetail(orderId) {
    window.location.href = `/pesanan-saya/${orderId}`;
}

// Rate order
function rateOrder(orderId) {
    window.location.href = `/pesanan-saya/${orderId}/rate`;
}

// Submit order rating
function submitRating(orderId, ratings, comments = {}) {
    const formData = new FormData();
    formData.append("order_id", orderId);

    // Add ratings
    Object.keys(ratings).forEach((productId) => {
        formData.append(`ratings[${productId}]`, ratings[productId]);
    });

    // Add comments if provided
    Object.keys(comments).forEach((productId) => {
        if (comments[productId]) {
            formData.append(`comments[${productId}]`, comments[productId]);
        }
    });

    const submitBtn = document.getElementById("submit-btn");
    if (submitBtn) {
        setLoadingState(submitBtn, true, "Mengirim rating...");
    }

    fetchWithErrorHandling(`/pesanan-saya/${orderId}/rate`, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
        },
    })
        .then((data) => {
            if (data.success) {
                showToast("Rating berhasil dikirim", "success");
                setTimeout(() => {
                    window.location.href = "/pesanan-saya";
                }, 1500);
            } else {
                showToast(data.message || "Gagal mengirim rating", "error");
            }
        })
        .catch((error) => {
            console.error("Submit rating error:", error);
            showToast("Terjadi kesalahan saat mengirim rating", "error");
        })
        .finally(() => {
            if (submitBtn) {
                setLoadingState(submitBtn, false);
            }
        });
}

// Rating functionality for rating page
let orderRatings = {};

function setRating(productId, rating) {
    orderRatings[productId] = rating;

    // Update star display
    const stars = document.querySelectorAll(`[data-product-id="${productId}"]`);
    stars.forEach((star, index) => {
        const starRating = index + 1;
        if (starRating <= rating) {
            star.classList.remove("text-gray-300");
            star.classList.add("text-yellow-400");
        } else {
            star.classList.remove("text-yellow-400");
            star.classList.add("text-gray-300");
        }
    });

    // Update hidden input
    const ratingInput = document.getElementById(`rating-${productId}`);
    if (ratingInput) {
        ratingInput.value = rating;
    }

    // Check if all products have ratings
    checkAllRatings();
}

function checkAllRatings() {
    const submitBtn = document.getElementById("submit-btn");
    if (!submitBtn) return;

    const totalProducts = parseInt(submitBtn.dataset.totalProducts || "0");
    const ratedProducts = Object.keys(orderRatings).filter(
        (productId) => orderRatings[productId] > 0
    ).length;

    if (ratedProducts === totalProducts) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("opacity-50", "cursor-not-allowed");
    }
}

// Tab functionality for order pages
function initializeOrderTabs() {
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
            const selectedTab = document.getElementById(tabName + "-tab");
            if (selectedTab) {
                selectedTab.classList.remove("hidden");
            }
        });
    });

    // Set initial active tab
    const activeTab = document.querySelector(".tab-button.active");
    if (activeTab) {
        activeTab.classList.add("text-blue-600", "border-blue-600");
        activeTab.classList.remove("text-gray-500", "border-transparent");
    }
}

// Order status helpers
function getOrderStatusText(status) {
    const statusMap = {
        pending: "Menunggu Pembayaran",
        paid: "Sudah Dibayar",
        processing: "Sedang Diproses",
        shipped: "Dikirim",
        delivered: "Selesai",
        cancelled: "Dibatalkan",
        refunded: "Dikembalikan",
    };
    return statusMap[status] || status;
}

function getOrderStatusColor(status) {
    const colorMap = {
        pending: "text-yellow-600 bg-yellow-100",
        paid: "text-blue-600 bg-blue-100",
        processing: "text-orange-600 bg-orange-100",
        shipped: "text-purple-600 bg-purple-100",
        delivered: "text-green-600 bg-green-100",
        cancelled: "text-red-600 bg-red-100",
        refunded: "text-gray-600 bg-gray-100",
    };
    return colorMap[status] || "text-gray-600 bg-gray-100";
}

// Export functions
window.cancelOrder = cancelOrder;
window.viewOrderDetail = viewOrderDetail;
window.rateOrder = rateOrder;
window.submitRating = submitRating;
window.setRating = setRating;
window.checkAllRatings = checkAllRatings;
window.initializeOrderTabs = initializeOrderTabs;
window.getOrderStatusText = getOrderStatusText;
window.getOrderStatusColor = getOrderStatusColor;
window.orderRatings = orderRatings;
