{{-- Register Header Component --}}
<div class="text-center">
    <h2 class="text-3xl font-bold text-gray-900">Register</h2>
    <p class="mt-2 text-sm text-gray-600">
        {{-- Already have an account? --}} Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
            {{--Sign in here--}} Masuk Disini
        </a>
    </p>
    <p class="mt-2 text-sm text-gray-600">
        Ingin menjual produk?
        <a href="{{ route('seller.register') }}" class="font-medium text-blue-600 hover:text-blue-500">
            Daftar sebagai penjual
        </a>
    </p>
</div>
