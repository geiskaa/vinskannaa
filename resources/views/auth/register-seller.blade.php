@extends('layouts.auth')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
            @include('components.auth.logo')
            <div class="mt-6 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Daftar Sebagai Seller
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Mulai berjualan dan kembangkan bisnis Anda bersama kami
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form method="POST" action="{{ route('seller.register.submit') }}" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <!-- Store Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Toko</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="store_name" class="block text-sm font-medium text-gray-700">
                                    Nama Toko <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="store_name" name="store_name" type="text" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('store_name') border-red-500 @enderror"
                                        value="{{ old('store_name') }}" placeholder="Masukkan nama toko">
                                </div>
                                @error('store_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="owner_name" class="block text-sm font-medium text-gray-700">
                                    Nama Pemilik <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="owner_name" name="owner_name" type="text" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('owner_name') border-red-500 @enderror"
                                        value="{{ old('owner_name') }}" placeholder="Masukkan nama pemilik">
                                </div>
                                @error('owner_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Deskripsi Toko
                            </label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3"
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                                    placeholder="Ceritakan tentang toko Anda...">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kontak</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="email" name="email" type="email" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                        value="{{ old('email') }}" placeholder="email@example.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="phone_number" name="phone_number" type="tel" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('phone_number') border-red-500 @enderror"
                                        value="{{ old('phone_number') }}" placeholder="08123456789">
                                </div>
                                @error('phone_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror"
                                        placeholder="Minimal 8 karakter">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Ulangi password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Alamat Toko</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="address" name="address" rows="2" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror"
                                        placeholder="Jl. Nama Jalan No. 123, RT/RW, Kelurahan">{{ old('address') }}</textarea>
                                </div>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">
                                        Kota <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input id="city" name="city" type="text" required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('city') border-red-500 @enderror"
                                            value="{{ old('city') }}" placeholder="Jakarta">
                                    </div>
                                    @error('city')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">
                                        Provinsi <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input id="province" name="province" type="text" required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('province') border-red-500 @enderror"
                                            value="{{ old('province') }}" placeholder="DKI Jakarta">
                                    </div>
                                    @error('province')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                        Kode Pos <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <input id="postal_code" name="postal_code" type="text" required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('postal_code') border-red-500 @enderror"
                                            value="{{ old('postal_code') }}" placeholder="12345">
                                    </div>
                                    @error('postal_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Store Images Section -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Toko</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">
                                    Logo Toko
                                </label>
                                <div class="mt-1">
                                    <input id="logo" name="logo" type="file" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('logo') border-red-500 @enderror">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                                @error('logo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="banner" class="block text-sm font-medium text-gray-700">
                                    Banner Toko
                                </label>
                                <div class="mt-1">
                                    <input id="banner" name="banner" type="file" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('banner') border-red-500 @enderror">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                                @error('banner')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            Saya menyetujui <a href="#" class="text-indigo-600 hover:text-indigo-500">syarat dan
                                ketentuan</a> yang berlaku
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Daftar Sebagai Seller
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Sudah memiliki akun seller?
                            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
