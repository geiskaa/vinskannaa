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

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

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
                            <button @click="activeTab = 'riwayat'"
                                :class="{ 'bg-indigo-50 text-indigo-600 border-indigo-200': activeTab === 'riwayat' }"
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
                            <p class="text-gray-600">Perbarui informasi profil dan alamat Anda.</p>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('name') border-red-300 @enderror"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('email') border-red-300 @enderror"
                                        required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('phone') border-red-300 @enderror"
                                        placeholder="+62 812 3456 7890">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                    <input type="date" name="date_of_birth"
                                        value="{{ old('date_of_birth', auth()->user()->date_of_birth ? auth()->user()->date_of_birth->format('Y-m-d') : '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors @error('date_of_birth') border-red-300 @enderror">
                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="gender" value="male"
                                                {{ old('gender', auth()->user()->gender) == 'male' ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="gender" value="female"
                                                {{ old('gender', auth()->user()->gender) == 'female' ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                                        </label>
                                    </div>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="border-t pt-6">
                                <div x-data="addressManager()">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <button type="button" @click="addNewAddress()"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Tambah Alamat
                                        </button>
                                    </div>

                                    <!-- Address List -->
                                    <div class="space-y-4">
                                        <template x-for="(address, index) in addresses" :key="index">
                                            <div class="border border-gray-200 rounded-xl p-4 relative">
                                                <!-- Delete Button -->
                                                <button type="button" @click="removeAddress(index)"
                                                    x-show="addresses.length > 1"
                                                    class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                                <!-- Address Label -->
                                                <div class="mb-3">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Label
                                                        Alamat</label>
                                                    <div class="flex flex-wrap gap-2">
                                                        <template x-for="label in addressLabels" :key="label">
                                                            <button type="button" @click="address.label = label"
                                                                :class="{
                                                                    'bg-indigo-100 text-indigo-700 border-indigo-300': address
                                                                        .label === label,
                                                                    'bg-gray-100 text-gray-700 border-gray-300': address
                                                                        .label !== label
                                                                }"
                                                                class="px-3 py-1 text-xs font-medium border rounded-full hover:bg-indigo-50 transition-colors"
                                                                x-text="label">
                                                            </button>
                                                        </template>
                                                    </div>
                                                    <!-- Custom Label Input -->
                                                    <input type="text" x-model="address.label"
                                                        placeholder="Atau ketik label custom..."
                                                        class="mt-2 w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                </div>

                                                <!-- Address Details Grid -->
                                                <div class="grid grid-cols-1 gap-4">
                                                    <!-- Full Address -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                                            Lengkap</label>
                                                        <textarea x-model="address.full_address" rows="2" placeholder="Jl. Contoh No. 123, RT 01/RW 02, Kelurahan..."
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"></textarea>
                                                    </div>

                                                    <!-- City, State, Postal Code -->
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                                                            <input type="text" x-model="address.city"
                                                                placeholder="Jakarta"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2">Provinsi</label>
                                                            <select x-model="address.state"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                                <option value="">Pilih Provinsi</option>
                                                                <option value="DKI Jakarta">DKI Jakarta</option>
                                                                <option value="Jawa Barat">Jawa Barat</option>
                                                                <option value="Jawa Tengah">Jawa Tengah</option>
                                                                <option value="Jawa Timur">Jawa Timur</option>
                                                                <option value="Banten">Banten</option>
                                                                <option value="Sumatera Utara">Sumatera Utara</option>
                                                                <option value="Sumatera Barat">Sumatera Barat</option>
                                                                <option value="Bali">Bali</option>
                                                                <option value="Yogyakarta">Yogyakarta</option>
                                                                <option value="Kalimantan Barat">Kalimantan Barat</option>
                                                                <option value="Kalimantan Tengah">Kalimantan Tengah
                                                                </option>
                                                                <option value="Kalimantan Selatan">Kalimantan Selatan
                                                                </option>
                                                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                                                                <option value="Sulawesi Utara">Sulawesi Utara</option>
                                                                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2">Kode
                                                                Pos</label>
                                                            <input type="text" x-model="address.postal_code"
                                                                placeholder="12345"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                        </div>
                                                    </div>

                                                    <!-- Additional Info -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2">Nama
                                                                Penerima</label>
                                                            <input type="text" x-model="address.recipient_name"
                                                                placeholder="Nama lengkap penerima"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-2">No.
                                                                Telepon</label>
                                                            <input type="tel" x-model="address.phone"
                                                                placeholder="+62 812 3456 7890"
                                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                                        </div>
                                                    </div>

                                                    <!-- Set as Primary -->
                                                    <div class="flex items-center">
                                                        <input type="checkbox" :id="'primary_' + index"
                                                            @change="setPrimary(index)" :checked="address.is_primary"
                                                            class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                        <label :for="'primary_' + index"
                                                            class="ml-2 text-sm text-gray-700">
                                                            Jadikan sebagai alamat utama
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Hidden Input for Form Submission -->
                                    <input type="hidden" name="addresses" :value="JSON.stringify(addresses)">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-6 border-t">
                                <button type="submit"
                                    class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Order History Tab -->
                    <div x-show="activeTab === 'riwayat'" class="bg-white rounded-2xl shadow-lg border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-900">Riwayat Pesanan</h2>
                            <p class="text-gray-600">Lihat semua pesanan yang pernah Anda buat.</p>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada pesanan</h3>
                                <p class="mt-2 text-gray-500">Mulai berbelanja untuk melihat riwayat pesanan Anda.</p>
                                <div class="mt-6">
                                    <a href="{{ route('dashboard') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                        Mulai Belanja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $formattedAddresses = !empty($addresses)
                    ? $addresses
                        ->map(function ($address) {
                            return [
                                'label' => $address->label,
                                'full_address' => $address->full_address,
                                'city' => $address->city,
                                'state' => $address->state,
                                'postal_code' => $address->postal_code,
                                'recipient_name' => $address->recipient_name,
                                'phone' => $address->phone,
                                'is_primary' => $address->is_primary,
                            ];
                        })
                        ->toArray()
                    : [
                        [
                            'label' => 'Rumah',
                            'full_address' => '',
                            'city' => '',
                            'state' => '',
                            'postal_code' => '',
                            'recipient_name' => auth()->user()->name,
                            'phone' => auth()->user()->phone,
                            'is_primary' => true,
                        ],
                    ];
            @endphp

        </div>
    </div>

    <script>
        function addressManager() {
            return {
                addresses: @json($formattedAddresses),
                addressLabels: ['Rumah', 'Kantor', 'Kost', 'Apartemen', 'Toko'],

                addNewAddress() {
                    this.addresses.push({
                        label: '',
                        full_address: '',
                        city: '',
                        state: '',
                        postal_code: '',
                        recipient_name: '{{ auth()->user()->name }}',
                        phone: '{{ auth()->user()->phone }}',
                        is_primary: false
                    });
                },

                removeAddress(index) {
                    if (this.addresses.length > 1) {
                        // If removing primary address, set first remaining as primary
                        if (this.addresses[index].is_primary && this.addresses.length > 1) {
                            const remainingIndex = index === 0 ? 1 : 0;
                            this.addresses[remainingIndex].is_primary = true;
                        }
                        this.addresses.splice(index, 1);
                    }
                },

                setPrimary(index) {
                    // Set all to false first
                    this.addresses.forEach(addr => addr.is_primary = false);
                    // Set selected as primary
                    this.addresses[index].is_primary = true;
                }
            }
        }
    </script>
@endsection
