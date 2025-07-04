/**
 * Rating functionality for products and orders
 */

let ratings = {};

/**
 * Set rating for a product
 * @param {string} productId - Product ID
 * @param {number} rating - Rating value (1-5)
 */
function setRating(productId, rating) {
    ratings[productId] = rating;

    // Update star display
    updateStarDisplay(productId, rating);

    // Update hidden input
    updateRatingInput(productId, rating);

    // Check if all products have ratings
    checkAllRatings();
}

/**
 * Update star display for a product
 * @param {string} productId - Product ID
 * @param {number} rating - Rating value
 */
function updateStarDisplay(productId, rating) {
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
}

/**
 * Update hidden rating input
 * @param {string} productId - Product ID
 * @param {number} rating - Rating value
 */
function updateRatingInput(productId, rating) {
    const ratingInput = document.getElementById(`rating-${productId}`);
    if (ratingInput) {
        ratingInput.value = rating;
    }
}

/**
 * Check if all products have ratings and enable/disable submit button
 */
function checkAllRatings() {
    const submitBtn = document.getElementById("submit-btn");
    if (!submitBtn) return;

    const totalProducts = parseInt(submitBtn.dataset.totalProducts || 0);
    const ratedProducts = Object.keys(ratings).filter(
        (productId) => ratings[productId] > 0
    ).length;

    if (ratedProducts === totalProducts && totalProducts > 0) {
        enableSubmitButton(submitBtn);
    } else {
        disableSubmitButton(submitBtn);
    }
}

/**
 * Enable submit button
 * @param {HTMLElement} submitBtn - Submit button element
 */
function enableSubmitButton(submitBtn) {
    submitBtn.disabled = false;
    submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
    submitBtn.classList.add("hover:bg-blue-700");
}

/**
 * Disable submit button
 * @param {HTMLElement} submitBtn - Submit button element
 */
function disableSubmitButton(submitBtn) {
    submitBtn.disabled = true;
    submitBtn.classList.add("opacity-50", "cursor-not-allowed");
    submitBtn.classList.remove("hover:bg-blue-700");
}

/**
 * Initialize rating functionality
 */
function initializeRating() {
    // Add event listeners to rating stars
    const ratingStars = document.querySelectorAll("[data-rating]");
    ratingStars.forEach((star) => {
        star.addEventListener("click", function () {
            const productId = this.dataset.productId;
            const rating = parseInt(this.dataset.rating);
            setRating(productId, rating);
        });

        // Add hover effects
        star.addEventListener("mouseenter", function () {
            const productId = this.dataset.productId;
            const rating = parseInt(this.dataset.rating);
            highlightStars(productId, rating);
        });
    });

    // Add mouse leave event to rating containers
    const ratingContainers = document.querySelectorAll(
        "[data-rating-container]"
    );
    ratingContainers.forEach((container) => {
        container.addEventListener("mouseleave", function () {
            const productId = this.dataset.productId;
            const currentRating = ratings[productId] || 0;
            updateStarDisplay(productId, currentRating);
        });
    });
}

/**
 * Highlight stars on hover
 * @param {string} productId - Product ID
 * @param {number} rating - Rating to highlight
 */
function highlightStars(productId, rating) {
    const stars = document.querySelectorAll(`[data-product-id="${productId}"]`);
    stars.forEach((star, index) => {
        const starRating = index + 1;
        if (starRating <= rating) {
            star.classList.add("text-yellow-400");
            star.classList.remove("text-gray-300");
        } else {
            star.classList.add("text-gray-300");
            star.classList.remove("text-yellow-400");
        }
    });
}

/**
 * Submit ratings for an order
 * @param {string} orderId - Order ID
 * @param {Object} formData - Additional form data
 */
function submitRatings(orderId, formData = {}) {
    const submitBtn = document.getElementById("submit-btn");
    if (!submitBtn || submitBtn.disabled) return;

    setLoadingState(submitBtn, true, "Mengirim rating...");

    const ratingData = {
        order_id: orderId,
        ratings: ratings,
        ...formData,
    };

    fetchWithErrorHandling(`/pesanan-saya/${orderId}/rate`, {
        method: "POST",
        body: JSON.stringify(ratingData),
    })
        .then((data) => {
            if (data.success) {
                showToast("Rating berhasil diberikan", "success");
                setTimeout(() => {
                    window.location.href = "/pesanan-saya";
                }, 1500);
            } else {
                showToast(data.message || "Gagal memberikan rating", "error");
            }
        })
        .catch((error) => {
            console.error("Error submitting ratings:", error);
            showToast("Terjadi kesalahan saat mengirim rating", "error");
        })
        .finally(() => {
            setLoadingState(submitBtn, false);
        });
}

/**
 * Get rating for a product
 * @param {string} productId - Product ID
 * @returns {number} Rating value
 */
function getRating(productId) {
    return ratings[productId] || 0;
}

/**
 * Clear all ratings
 */
function clearAllRatings() {
    ratings = {};

    // Reset all star displays
    const allStars = document.querySelectorAll("[data-rating]");
    allStars.forEach((star) => {
        star.classList.remove("text-yellow-400");
        star.classList.add("text-gray-300");
    });

    // Reset all rating inputs
    const allRatingInputs = document.querySelectorAll('[id^="rating-"]');
    allRatingInputs.forEach((input) => {
        input.value = 0;
    });

    checkAllRatings();
}

/**
 * Validate ratings before submission
 * @returns {boolean} True if all ratings are valid
 */
function validateRatings() {
    const submitBtn = document.getElementById("submit-btn");
    if (!submitBtn) return false;

    const totalProducts = parseInt(submitBtn.dataset.totalProducts || 0);
    const ratedProducts = Object.keys(ratings).filter(
        (productId) => ratings[productId] > 0
    ).length;

    if (ratedProducts < totalProducts) {
        showToast("Mohon beri rating untuk semua produk", "error");
        return false;
    }

    // Check if all ratings are between 1-5
    const invalidRatings = Object.values(ratings).filter(
        (rating) => rating < 1 || rating > 5
    );

    if (invalidRatings.length > 0) {
        showToast("Rating harus antara 1-5 bintang", "error");
        return false;
    }

    return true;
}

// Initialize rating functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    initializeRating();
});

// Make functions available globally
window.setRating = setRating;
window.getRating = getRating;
window.clearAllRatings = clearAllRatings;
window.submitRatings = submitRatings;
window.validateRatings = validateRatings;
window.initializeRating = initializeRating;
