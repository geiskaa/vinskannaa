/**
 * Utility functions shared across multiple pages
 */

// Toast notification function
function showToast(message, type = "success") {
    const toastContainer = document.getElementById("toast-container");

    if (!toastContainer) {
        console.error("Toast container not found");
        return;
    }

    // Create toast element
    const toast = document.createElement("div");
    toast.className = `transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

    // Toast content based on type
    let bgColor, icon;

    switch (type) {
        case "success":
            bgColor = "bg-green-500";
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>`;
            break;
        case "error":
            bgColor = "bg-red-500";
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`;
            break;
        case "info":
            bgColor = "bg-blue-500";
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
            break;
        default:
            bgColor = "bg-gray-500";
            icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
    }

    toast.innerHTML = `
        <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3 min-w-64 max-w-sm">
            <div class="flex-shrink-0">
                ${icon}
            </div>
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

    // Trigger animation after a brief delay
    setTimeout(() => {
        toast.classList.remove("translate-x-full", "opacity-0");
        toast.classList.add("translate-x-0", "opacity-100");
    }, 100);

    // Auto remove after 4 seconds
    setTimeout(() => {
        removeToast(toast);
    }, 4000);
}
function changeMainImage(imageSrc, thumbnailElement) {
    const mainImage = document.getElementById("main-image");
    if (mainImage) {
        mainImage.src = imageSrc;
    }

    // Update thumbnail borders
    document.querySelectorAll(".thumbnail-image").forEach((thumb) => {
        thumb.classList.remove("border-gray-400");
        thumb.classList.add("border-transparent");
    });

    thumbnailElement.classList.remove("border-transparent");
    thumbnailElement.classList.add("border-gray-400");
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

// CSRF Token helper
function getCsrfToken() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error("CSRF token not found");
        return null;
    }
    return csrfToken.getAttribute("content");
}

// Common fetch with error handling
function fetchWithErrorHandling(url, options = {}) {
    const csrfToken = getCsrfToken();

    const defaultOptions = {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...options.headers,
        },
        credentials: "same-origin",
    };

    if (
        csrfToken &&
        (options.method === "POST" ||
            options.method === "PUT" ||
            options.method === "DELETE")
    ) {
        defaultOptions.headers["X-CSRF-TOKEN"] = csrfToken;
    }

    const finalOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers,
        },
    };

    return fetch(url, finalOptions)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch((error) => {
            console.error("Fetch error:", error);
            throw error;
        });
}

// Common confirmation dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Loading state helpers
function setLoadingState(element, isLoading, loadingText = "Memuat...") {
    if (!element) return;

    const originalText = element.dataset.originalText || element.textContent;
    element.dataset.originalText = originalText;

    if (isLoading) {
        element.disabled = true;
        element.classList.add("animate-pulse");
        element.innerHTML = `
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                ${loadingText}
            </div>
        `;
    } else {
        element.disabled = false;
        element.classList.remove("animate-pulse");
        element.textContent = originalText;
    }
}

// Animation helpers
function addBounceAnimation(element, duration = 600) {
    if (!element) return;

    element.classList.add("animate-bounce");
    setTimeout(() => {
        element.classList.remove("animate-bounce");
    }, duration);
}

function addShakeAnimation(element, duration = 600) {
    if (!element) return;

    element.classList.add("animate-shake");
    setTimeout(() => {
        element.classList.remove("animate-shake");
    }, duration);
}

// URL parameter helpers
function getUrlParameter(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function updateUrlParameter(param, value) {
    const url = new URL(window.location);
    if (value) {
        url.searchParams.set(param, value);
    } else {
        url.searchParams.delete(param);
    }
    window.history.replaceState({}, "", url);
}

// Form validation helpers
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePhone(phone) {
    const phoneRegex = /^[\+]?[0-9]{10,15}$/;
    return phoneRegex.test(phone.replace(/\s/g, ""));
}

// Number formatting helpers
function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat("id-ID").format(number);
}

// Date formatting helpers
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

// Debounce utility
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Make functions available globally
window.showToast = showToast;
window.changeMainImage = changeMainImage;
window.removeToast = removeToast;
window.getCsrfToken = getCsrfToken;
window.fetchWithErrorHandling = fetchWithErrorHandling;
window.confirmAction = confirmAction;
window.setLoadingState = setLoadingState;
window.addBounceAnimation = addBounceAnimation;
window.addShakeAnimation = addShakeAnimation;
window.getUrlParameter = getUrlParameter;
window.updateUrlParameter = updateUrlParameter;
window.validateEmail = validateEmail;
window.validatePhone = validatePhone;
window.formatCurrency = formatCurrency;
window.formatNumber = formatNumber;
window.formatDate = formatDate;
window.formatDateTime = formatDateTime;
window.debounce = debounce;
