@extends('layouts.seller')

@section('title', 'Product Details - ThriftMart')
@section('page-title', 'Product Details')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm text-gray-500">
            <a href="{{ route('seller.dashboard') }}" class="hover:text-gray-700">Home</a>
            <span class="mx-2">></span>
            <a href="{{ route('seller.products') }}" class="hover:text-gray-700">All Products</a>
            <span class="mx-2">></span>
            <span class="text-gray-900">Product Details</span>
        </nav>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Side - Product Form -->
                    <div class="space-y-6">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Nama Produk -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Produk
                                </label>
                                <input type="text" name="name" value="Baju Hitam"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea name="description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Deskripsi produk...">Baju Hitam bahan cotton</textarea>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori
                                </label>
                                <select name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Kategori</option>
                                    <option value="T-Shirt" selected>T-Shirt</option>
                                    <option value="Kemeja">Kemeja</option>
                                    <option value="Celana">Celana</option>
                                    <option value="Jaket">Jaket</option>
                                </select>
                            </div>

                            <!-- Nama Brand -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Brand
                                </label>
                                <input type="text" name="brand" value="ROWN"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Unit Stok & Jumlah Stok -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Unit stok barang
                                    </label>
                                    <input type="text" name="sku" value="#32A53"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah stok
                                    </label>
                                    <input type="number" name="stock" value="211"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Harga Regular & Harga Jual -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Harga reguler
                                    </label>
                                    <input type="text" name="regular_price" value="Rp.40.000"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Harga jual
                                    </label>
                                    <input type="text" name="sale_price" value="Rp.50.000"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            <!-- Tag -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tag
                                </label>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-800 text-white">
                                        Cotton
                                        <button type="button" class="ml-2 text-gray-300 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-800 text-white">
                                        Hitam
                                        <button type="button" class="ml-2 text-gray-300 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <input type="text" name="tags" placeholder="Tambah tag..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </form>
                    </div>

                    <!-- Right Side - Product Image & Gallery -->
                    <div class="space-y-6">
                        <!-- Main Product Image -->
                        <div class="bg-gray-50 rounded-lg p-8 flex items-center justify-center">
                            <div class="w-64 h-64 bg-white rounded-lg shadow-sm flex items-center justify-center">
                                <img src="/api/placeholder/256/256" alt="Product Image"
                                    class="w-full h-full object-cover rounded-lg">
                            </div>
                        </div>

                        <!-- Product Gallery -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Gallery</h3>

                            <!-- Image Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-6">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">Drop your images here, or browse</p>
                                    <p class="text-sm text-gray-400">Jpeg, png are allowed</p>
                                </div>
                            </div>

                            <!-- Image List -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-300 rounded"></div>
                                        <span class="text-sm text-gray-700">Product thumbnail.png</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full w-full"></div>
                                        </div>
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-300 rounded"></div>
                                        <span class="text-sm text-gray-700">Product thumbnail.png</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full w-full"></div>
                                        </div>
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-300 rounded"></div>
                                        <span class="text-sm text-gray-700">Product thumbnail.png</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full w-full"></div>
                                        </div>
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gray-300 rounded"></div>
                                        <span class="text-sm text-gray-700">Product thumbnail.png</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-32 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full w-full"></div>
                                        </div>
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <button type="button"
                        class="px-8 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        TAMBAH
                    </button>
                    <button type="button"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        HAPUS
                    </button>
                    <button type="button"
                        class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        BATAL
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Add tag functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tagInput = document.querySelector('input[name="tags"]');
                const tagContainer = tagInput.previousElementSibling;

                tagInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const tagText = this.value.trim();
                        if (tagText) {
                            addTag(tagText);
                            this.value = '';
                        }
                    }
                });

                function addTag(text) {
                    const tagElement = document.createElement('span');
                    tagElement.className =
                        'inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-800 text-white';
                    tagElement.innerHTML = `
            ${text}
            <button type="button" class="ml-2 text-gray-300 hover:text-white" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
                    tagContainer.appendChild(tagElement);
                }
            });
        </script>
    @endpush
@endsection
