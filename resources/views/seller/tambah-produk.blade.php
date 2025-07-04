@extends('layouts.seller')

@section('title', 'Tambah Produk - ThriftMart')
@section('page-title', 'TAMBAH PRODUK')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">TAMBAH PRODUK</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="" class="hover:text-gray-700">Home</a>
                <span class="mx-1">></span>
                <a href="" class="hover:text-gray-700">Semua Produk</a>
                <span class="mx-1">></span>
                <span>Tambah Produk</span>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <form action="" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf

                    <!-- Product Images -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Produk</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <div id="imagePreview" class="hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
                                <!-- Preview images will be inserted here -->
                            </div>
                            <div id="uploadArea" class="space-y-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg mx-auto flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Pilih foto produk atau drag & drop di sini</p>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG (Max 5MB per file)</p>
                                </div>
                                <input type="file" name="images[]" id="productImages" multiple accept="image/*"
                                    class="hidden">
                                <button type="button" onclick="document.getElementById('productImages').click()"
                                    class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                    Pilih Foto
                                </button>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Produk</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                    Produk</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Masukkan nama produk">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Section -->
                            <div>
                                <label for="section" class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                                <select name="section" id="section"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('section') border-red-500 @enderror">
                                    <option value="">Pilih Bagian</option>
                                    <option value="men" {{ old('section') == 'men' ? 'selected' : '' }}>Pria</option>
                                    <option value="women" {{ old('section') == 'women' ? 'selected' : '' }}>Wanita</option>
                                    <option value="kids" {{ old('section') == 'kids' ? 'selected' : '' }}>Anak-anak
                                    </option>
                                </select>
                                @error('section')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                <select name="category" id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('category') border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    <option value="kemeja" {{ old('category') == 'kemeja' ? 'selected' : '' }}>Kemeja
                                    </option>
                                    <option value="kaos" {{ old('category') == 'kaos' ? 'selected' : '' }}>Kaos</option>
                                    <option value="jaket" {{ old('category') == 'jaket' ? 'selected' : '' }}>Jaket
                                    </option>
                                    <option value="celana_panjang"
                                        {{ old('category') == 'celana_panjang' ? 'selected' : '' }}>Celana Panjang</option>
                                    <option value="celana_pendek"
                                        {{ old('category') == 'celana_pendek' ? 'selected' : '' }}>Celana Pendek</option>
                                    <option value="hoodie" {{ old('category') == 'hoodie' ? 'selected' : '' }}>Hoodie
                                    </option>
                                    <option value="dress" {{ old('category') == 'dress' ? 'selected' : '' }}>Dress
                                    </option>
                                    <option value="rok" {{ old('category') == 'rok' ? 'selected' : '' }}>Rok</option>
                                    <option value="sweater" {{ old('category') == 'sweater' ? 'selected' : '' }}>Sweater
                                    </option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}"
                                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('price') border-red-500 @enderror"
                                        placeholder="0">
                                </div>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('stock') border-red-500 @enderror"
                                    placeholder="0">
                                @error('stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi
                                    Produk</label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="Jelaskan detail produk Anda...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href=""
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tambah Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productImages = document.getElementById('productImages');
            const imagePreview = document.getElementById('imagePreview');
            const uploadArea = document.getElementById('uploadArea');
            let selectedFiles = [];

            // Handle file selection
            productImages.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                selectedFiles = files.slice(0, 5); // Limit to 5 images

                if (selectedFiles.length > 0) {
                    displayImagePreviews();
                }
            });

            // Display image previews
            function displayImagePreviews() {
                imagePreview.innerHTML = '';

                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'relative group';
                        imageContainer.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImage(${index})" 
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                ×
                            </button>
                        `;
                        imagePreview.appendChild(imageContainer);
                    };
                    reader.readAsDataURL(file);
                });

                // Show/hide elements
                imagePreview.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            }

            // Remove image function
            window.removeImage = function(index) {
                selectedFiles.splice(index, 1);

                if (selectedFiles.length === 0) {
                    imagePreview.classList.add('hidden');
                    uploadArea.classList.remove('hidden');
                    productImages.value = '';
                } else {
                    displayImagePreviews();
                }

                // Update file input
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                productImages.files = dt.files;
            };

            // Drag and drop functionality
            const dropArea = document.querySelector('.border-dashed');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropArea.classList.add('border-gray-400', 'bg-gray-50');
            }

            function unhighlight(e) {
                dropArea.classList.remove('border-gray-400', 'bg-gray-50');
            }

            dropArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = Array.from(dt.files).filter(file => file.type.startsWith('image/'));

                if (files.length > 0) {
                    selectedFiles = files.slice(0, 5);

                    // Update file input
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => dataTransfer.items.add(file));
                    productImages.files = dataTransfer.files;

                    displayImagePreviews();
                }
            }

            // Form validation
            const form = document.getElementById('productForm');
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const section = document.getElementById('section').value;
                const category = document.getElementById('category').value;
                const price = document.getElementById('price').value;
                const stock = document.getElementById('stock').value;

                if (!name || !section || !category || !price || !stock) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return false;
                }

                if (selectedFiles.length === 0) {
                    e.preventDefault();
                    alert('Mohon pilih minimal 1 foto produk!');
                    return false;
                }
            });
        });
    </script>
@endpush
