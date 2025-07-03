let ratings = {};

function setRating(productId, rating) {
    ratings[productId] = rating;

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
    document.getElementById(`rating-${productId}`).value = rating;

    // Check if all products have ratings
    checkAllRatings();
}

function checkAllRatings() {
    const totalProducts = parseInt(
        document.getElementById("submit-btn").dataset.totalProducts
    );
    const ratedProducts = Object.keys(ratings).filter(
        (productId) => ratings[productId] > 0
    ).length;

    const submitBtn = document.getElementById("submit-btn");
    if (ratedProducts === totalProducts) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("opacity-50", "cursor-not-allowed");
    }
}

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
