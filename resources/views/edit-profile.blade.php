@extends('layouts.app')

@section('title', 'Edit Profile - ThriftHub')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                        <p class="mt-2 text-gray-600">Kelola informasi profil dan pengaturan akun Anda</p>
                    </div>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Profile Photo -->
                        <div class="p-6 text-center bg-gradient-to-br from-indigo-500 to-purple-600">
                            <div class="relative inline-block">
                                <div
                                    class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-indigo-600 shadow-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <button
                                    class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-white">{{ auth()->user()->name }}</h3>
                            <p class="text-indigo-100">{{ auth()->user()->email }}</p>
                        </div>

                        <!-- Navigation Menu -->
                        <nav class="p-4 space-y-2" x-data="{ activeTab: 'profile' }">
                            <button @click="activeTab = 'profile'"
                                :class="{ 'bg-indigo-50 text-indigo-600 border-indigo-200': activeTab === 'profile' }"
                                class="w-full flex items-center px-4 py-3 text-left text-sm font-medium text-gray-700 border border-transparent rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Informasi Pribadi
                            </button>
                            <button @click="activeTab = 'Riwayat'"
                                :class="{ 'bg-indigo-50 text-indigo-600 border-indigo-200': activeTab === 'Riwayat' }"
                                class="w-full flex items-center px-4 py-3 text-left text-sm font-medium text-gray-700 border border-transparent rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Riwayat Pesanan
                            </button>

                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2" x-data="{ activeTab: 'profile' }">
                    <!-- Profile Information Tab -->
                    <div x-show="activeTab === 'profile'" class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900">Informasi Pribadi</h2>
                            <p class="text-gray-600">Perbarui informasi profil dan alamat email Anda.</p>
                        </div>

                        <form class="p-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" name="phone" placeholder="+62 812 3456 7890"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap Anda"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- City -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                    <input type="text" name="city" placeholder="Jakarta"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                </div>

                                <!-- State -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                    <select name="state"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                        <option value="">Pilih Provinsi</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Jawa Tengah">Jawa Tengah</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Banten">Banten</option>
                                    </select>
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="postal_code" placeholder="12345"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center">
                                        <input type="radio" name="gender" value="male"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="gender" value="female"
                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                                <button type="button"
                                    class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="p-6" x-show="activeTab === 'profile'">
                        @if (isset($completedOrders) && $completedOrders->count() > 0)
                            <div class="space-y-6">
                                @foreach ($completedOrders as $order)
                                    <div
                                        class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                        <!-- Order Header -->
                                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-900">
                                                            Pesanan #{{ $order->order_number ?? $order->id }}
                                                        </h3>
                                                        <p class="text-sm text-gray-600">
                                                            {{ $order->created_at->format('d M Y, H:i') }} WIB
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Items -->
                                        <div class="p-6">
                                            <div class="space-y-4">
                                                @foreach ($order->orderItems as $item)
                                                    <div class="flex items-center space-x-4">
                                                        <!-- Product Image -->
                                                        <div
                                                            class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                                            @if ($item->product->images && count($item->product->images) > 0)
                                                                <img src="{{ asset('storage/' . $item->product->images[0]) }}"
                                                                    alt="{{ $item->product->name }}"
                                                                    class="w-full h-full object-cover">
                                                            @else
                                                                <div
                                                                    class="w-full h-full flex items-center justify-center text-gray-400">
                                                                    <svg class="w-6 h-6" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Product Details -->
                                                        <div class="flex-1 min-w-0">
                                                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $item->product->name }}
                                                            </h4>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $item->product->category->name ?? 'Tanpa Kategori' }}
                                                            </p>
                                                            <div class="flex items-center mt-1 space-x-4">
                                                                <span class="text-sm text-gray-600">
                                                                    Qty: {{ $item->quantity }}
                                                                </span>
                                                                <span class="text-sm font-medium text-gray-900">
                                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Subtotal -->
                                                        <div class="flex-shrink-0 text-right">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                Rp
                                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Order Summary -->
                                            <div class="mt-6 pt-6 border-t border-gray-200">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4">
                                                        <span class="text-sm text-gray-600">
                                                            {{ $order->orderItems->count() }} item(s)
                                                        </span>
                                                        @if ($order->shipping_address)
                                                            <span class="text-sm text-gray-600">
                                                                • Dikirim ke:
                                                                {{ Str::limit($order->shipping_address, 30) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-sm text-gray-600">Total Pesanan</p>
                                                        <p class="text-lg font-bold text-gray-900">
                                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="mt-4 flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        @if ($order->completed_at)
                                                            <span class="text-xs text-gray-500">
                                                                Selesai pada
                                                                {{ $order->completed_at->format('d M Y, H:i') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <button onclick="viewOrderDetails({{ $order->id }})"
                                                            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                                            Lihat Detail
                                                        </button>
                                                        @if ($order->can_reorder)
                                                            <button onclick="reorderItems({{ $order->id }})"
                                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                                                                <svg class="w-3 h-3 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                    </path>
                                                                </svg>
                                                                Pesan Lagi
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if ($completedOrders->hasPages())
                                <div class="mt-8 flex justify-center">
                                    {{ $completedOrders->links() }}
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div
                                    class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Pesanan</h3>
                                <p class="text-gray-600 mb-6">Anda belum memiliki pesanan yang selesai.</p>
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Mulai Belanja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        @endpush
    @endsection
