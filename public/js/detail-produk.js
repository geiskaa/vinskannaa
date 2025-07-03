// Function to change main image
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

// Include the same favorite and cart toggle functions from your existing code
function toggleFavorite(productId) {
    // Add your favorite toggle logic here
    console.log("Toggle favorite for product:", productId);
}

function toggleCart(productId) {
    // Add your cart toggle logic here
    console.log("Toggle cart for product:", productId);
}
