{{-- resources/views/about.blade.php --}}
@extends('layouts.app')

@section('title', 'Tentang - Vinskanna')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">Vinskanna</h1>
            <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                Vinskanna adalah aplikasi berbasis web yang menyediakan platform jual beli produk florist
                seperti bunga segar, buket, tanaman hias, dan aksesoris dekoratif lainnya. Aplikasi ini
                dirancang untuk memudahkan proses transaksi antara penjual (admin) dan pembeli,
                sekaligus meningkatkan pengalaman belanja produk florist secara aman, nyaman,
                dan efisien.
            </p>
        </div>

        <!-- Image Gallery -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <!-- Image 1 -->
            <div class="relative overflow-hidden rounded-lg shadow-md group">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&h=600&fit=crop"
                    alt="Thrift Fashion Collection"
                    class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all duration-300">
                </div>
            </div>

            <!-- Image 2 -->
            <div class="relative overflow-hidden rounded-lg shadow-md group">
                <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&h=600&fit=crop"
                    alt="Vintage Clothing Style"
                    class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all duration-300">
                </div>
            </div>

            <!-- Image 3 -->
            <div class="relative overflow-hidden rounded-lg shadow-md group">
                <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=400&h=600&fit=crop"
                    alt="White Thrift Pants"
                    class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all duration-300">
                </div>
            </div>

            <!-- Image 4 -->
            <div class="relative overflow-hidden rounded-lg shadow-md group">
                <img src="https://images.unsplash.com/photo-1542327897-d73f4005b533?w=400&h=600&fit=crop"
                    alt="White Thrift Shirts"
                    class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all duration-300">
                </div>
            </div>
        </div>

        <!-- Mission & Vision Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Mission -->
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Misi Kami</h2>
                <ul class="space-y-4 text-gray-600">
                    <li class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Memberikan platform yang mudah dan aman untuk jual beli produk thrift berkualitas</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Mendukung gaya hidup berkelanjutan dengan mendaur ulang pakaian dan aksesoris</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Memberikan akses ke fashion berkualitas dengan harga terjangkau</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-green-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Membangun komunitas pencinta fashion berkelanjutan</span>
                    </li>
                </ul>
            </div>

            <!-- Vision -->
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Visi Kami</h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Menjadi platform terdepan untuk jual beli produk thrift di Indonesia, menciptakan
                    ekosistem fashion berkelanjutan yang menguntungkan semua pihak.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">Sustainable Fashion</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600">Ekonomi Sirkular</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-gray-600">Komunitas Berkelanjutan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Nilai-Nilai Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Quality -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Kualitas</h3>
                    <p class="text-gray-600">Setiap produk yang kami jual telah melalui seleksi ketat untuk memastikan
                        kualitas terbaik.</p>
                </div>

                <!-- Sustainability -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Berkelanjutan</h3>
                    <p class="text-gray-600">Berkomitmen untuk mengurangi limbah fashion dengan memperpanjang siklus hidup
                        produk.</p>
                </div>

                <!-- Community -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Komunitas</h3>
                    <p class="text-gray-600">Membangun komunitas yang saling mendukung dalam gerakan fashion berkelanjutan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="bg-gray-50 rounded-lg p-8 mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Pencapaian Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-blue-600 mb-2">1000+</div>
                    <div class="text-gray-600">Produk Terjual</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600 mb-2">500+</div>
                    <div class="text-gray-600">Pelanggan Puas</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-purple-600 mb-2">50+</div>
                    <div class="text-gray-600">Brand Tersedia</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-orange-600 mb-2">2kg</div>
                    <div class="text-gray-600">Limbah Tekstil Dikurangi</div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Bergabunglah dengan Komunitas Kami</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                Mulai berbelanja produk thrift berkualitas dan dukung gerakan fashion berkelanjutan bersama Vinskanna.
            </p>
            <a href="/dashboard"
                class="inline-flex items-center space-x-2 bg-gray-900 text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition-colors">
                <span class="text-lg font-medium">Jelajahi Produk</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
@endsection
