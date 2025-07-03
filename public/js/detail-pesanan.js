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

function cancelOrder(orderId) {
    if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) {
        fetch(`/pesanan-saya/${orderId}/cancel`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
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
