@extends('layouts.seller')

@section('title', 'Edit Produk - ThriftMart')
@section('page-title', 'Edit Produk')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Produk</h1>
                    <p class="text-gray-600 mt-1">Ubah informasi produk Anda</p>
                </div>
                <a href="{{ route('seller.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('seller.update-produk', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Product Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                        <div class="space-y-4">
                            <!-- Product Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $product->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                    placeholder="Masukkan nama produk">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Section & Category -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="section" class="block text-sm font-medium text-gray-700 mb-2">
                                        Seksi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="section" id="section"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('section') border-red-500 @enderror">
                                        <option value="">Pilih Seksi</option>
                                        <option value="men"
                                            {{ old('section', $product->section) == 'men' ? 'selected' : '' }}>Pria</option>
                                        <option value="women"
                                            {{ old('section', $product->section) == 'women' ? 'selected' : '' }}>Wanita
                                        </option>
                                        <option value="kids"
                                            {{ old('section', $product->section) == 'kids' ? 'selected' : '' }}>Anak-anak
                                        </option>
                                    </select>
                                    @error('section')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <select name="category" id="category"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                                        <option value="">Pilih Kategori</option>
                                        <option value="kemeja"
                                            {{ old('category', $product->category) == 'kemeja' ? 'selected' : '' }}>Kemeja
                                        </option>
                                        <option value="kaos"
                                            {{ old('category', $product->category) == 'kaos' ? 'selected' : '' }}>Kaos
                                        </option>
                                        <option value="jaket"
                                            {{ old('category', $product->category) == 'jaket' ? 'selected' : '' }}>Jaket
                                        </option>
                                        <option value="celana_panjang"
                                            {{ old('category', $product->category) == 'celana_panjang' ? 'selected' : '' }}>
                                            Celana Panjang</option>
                                        <option value="celana_pendek"
                                            {{ old('category', $product->category) == 'celana_pendek' ? 'selected' : '' }}>
                                            Celana Pendek</option>
                                        <option value="hoodie"
                                            {{ old('category', $product->category) == 'hoodie' ? 'selected' : '' }}>Hoodie
                                        </option>
                                        <option value="dress"
                                            {{ old('category', $product->category) == 'dress' ? 'selected' : '' }}>Dress
                                        </option>
                                        <option value="rok"
                                            {{ old('category', $product->category) == 'rok' ? 'selected' : '' }}>Rok
                                        </option>
                                        <option value="sweater"
                                            {{ old('category', $product->category) == 'sweater' ? 'selected' : '' }}>
                                            Sweater</option>
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price & Stock -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Harga <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                        <input type="number" name="price" id="price"
                                            value="{{ old('price', $product->price) }}" step="0.01"
                                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                            placeholder="0">
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock"
                                        value="{{ old('stock', $product->stock) }}" min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('stock') border-red-500 @enderror"
                                        placeholder="0">
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi Produk
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Gambar Produk</h2>

                        <!-- Combined Images Display -->
                        <div class="mb-4">
                            <div id="allImages" class="grid grid-cols-2 md:grid-cols-3 gap-4 min-h-[100px]">
                                <!-- Existing Images -->
                                @php
                                    $images = is_string($product->images)
                                        ? json_decode($product->images)
                                        : $product->images;
                                @endphp
                                @if ($images)
                                    @foreach ($images as $index => $image)
                                        <div class="relative group existing-image image-item"
                                            data-image="{{ $image }}">

                                            <img src="{{ asset('storage/' . $image) }}" alt="Product Image"
                                                class="w-full h-24 object-cover rounded-lg border border-gray-200">

                                            <!-- Delete Button -->
                                            <button type="button"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                onclick="removeExistingImage(this, '{{ $image }}')">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>

                                            <!-- Image Status Badge -->
                                            <div
                                                class="absolute bottom-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                                Existing
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @php
                                $images = is_string($product->images)
                                    ? json_decode($product->images)
                                    : $product->images;
                            @endphp

                            <!-- Show message if no images -->
                            <div id="noImagesMessage"
                                class="text-center text-gray-500 py-8  {{ $images && count($images) > 0 ? 'hidden' : '' }}">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada gambar produk</p>
                            </div>
                        </div>

                        <!-- Upload Area -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                            <input type="file" name="new_images[]" id="images" multiple accept="image/*"
                                class="hidden" class="hidden" onchange="addNewImages(this)">
                            <label for="images" class="cursor-pointer">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Tambah gambar baru</span>
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG hingga 5MB</p>
                            </label>
                        </div>

                        <!-- Hidden inputs for removed images -->
                        <div id="removedImages"></div>

                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Actions & Info -->
                <div class="space-y-6">
                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h2>

                        <div class="space-y-3">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update Produk
                            </button>

                            <a href="{{ route('seller.all-produk') }}"
                                class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center block">
                                Batal
                            </a>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dibuat:</span>
                                <span class="font-medium">{{ $product->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diupdate:</span>
                                <span class="font-medium">{{ $product->updated_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Slug:</span>
                                <span class="font-medium text-blue-600">{{ $product->slug }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rating:</span>
                                <span class="font-medium">{{ number_format($product->ratings, 1) }}/5</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-yellow-800 mb-2">💡 Tips</h3>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Gunakan gambar berkualitas tinggi</li>
                            <li>• Tulis deskripsi yang detail</li>
                            <li>• Pastikan harga kompetitif</li>
                            <li>• Update stok secara berkala</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let newImageFiles = [];
        let newImageIndex = 0;

        function addNewImages(input) {
            const container = document.getElementById('allImages');
            const currentImageCount = container.querySelectorAll('.image-item').length;

            if (input.files && input.files.length > 0) {
                // Check total image limit
                if (currentImageCount + input.files.length > 10) {
                    alert('Maksimal 10 gambar yang dapat diupload');
                    input.value = '';
                    return;
                }

                Array.from(input.files).forEach((file, index) => {
                    // Validate file size (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert(`File ${file.name} terlalu besar. Maksimal 5MB`);
                        return;
                    }

                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert(`File ${file.name} bukan gambar`);
                        return;
                    }

                    // Store file for form submission
                    newImageFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group image-item';
                        div.setAttribute('data-type', 'new');
                        div.setAttribute('data-index', newImageIndex);

                        div.innerHTML = `
                    <img src="${e.target.result}" 
                         alt="New Image ${newImageIndex + 1}" 
                         class="w-full h-24 object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="removeNewImage(this, ${newImageIndex})"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="absolute bottom-1 left-1 bg-green-600 bg-opacity-80 text-white text-xs px-2 py-1 rounded">
                        New
                    </div>
                `;

                        container.appendChild(div);
                        newImageIndex++;
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Update file input with new files
            updateFileInput();
        }

        function removeExistingImage(button, imagePath) {
            console.log('Removing existing image:', imagePath);
            const imageItem = button.closest('.image-item');

            // Create hidden input to track removed image
            const removedInput = document.createElement('input');
            removedInput.type = 'hidden';
            removedInput.name = 'removed_images[]';
            removedInput.value = imagePath;

            document.getElementById('removedImages').appendChild(removedInput);

            // Remove from DOM
            imageItem.remove();
        }

        function removeNewImage(button, index) {
            const imageItem = button.closest('.image-item');

            // Find and remove from newImageFiles array
            const fileIndex = newImageFiles.findIndex((file, i) => i === index);
            if (fileIndex !== -1) {
                newImageFiles.splice(fileIndex, 1);
            }

            // Remove from DOM
            imageItem.remove();

            // Update file input
            updateFileInput();
        }

        function updateFileInput() {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            // Add remaining files to DataTransfer
            newImageFiles.forEach(file => {
                dt.items.add(file);
            });

            // Update input files
            input.files = dt.files;
        }

        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.border-dashed');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadArea.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight(e) {
                uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                const input = document.getElementById('images');
                input.files = files;
                addNewImages(input);
            }
        });
    </script>
@endsection
