@extends('layouts.app')

@section('title', 'Beri Rating - ThriftHub')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('detailpesanan', $order->id) }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Detail Pesanan
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Beri Rating untuk Pesanan</h1>
                <p class="text-sm text-gray-500 mt-1">Order #{{ $order->order_id }}</p>
            </div>

            <form action="{{ route('orders.rate.store', $order->id) }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    @foreach ($order->items as $item)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    @if ($item->product && $item->product->image)
                                        <img src="{{ Str::startsWith($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}</h3>
                                    @if ($item->product && $item->product->category)
                                        <p class="text-sm text-gray-500">{{ $item->product->category }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>

                                    <!-- Rating Stars -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                        <div class="flex items-center space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <button type="button"
                                                    class="star-btn w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors"
                                                    data-product-id="{{ $item->product->id }}"
                                                    data-rating="{{ $i }}"
                                                    onclick="setRating({{ $item->product->id }}, {{ $i }})">
                                                    <svg class="w-full h-full fill-current" viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="ratings[{{ $loop->index }}][product_id]"
                                            value="{{ $item->product->id }}">
                                        <input type="hidden" name="ratings[{{ $loop->index }}][rating]" value="0"
                                            id="rating-{{ $item->product->id }}">
                                    </div>

                                    <!-- Review Text -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Ulasan
                                            (Opsional)
                                        </label>
                                        <textarea name="ratings[{{ $loop->index }}][review]" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-8 space-x-3">
                    <a href="{{ route('detailpesanan', $order->id) }}"
                        class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-btn" data-total-products="{{ count($order->items) }}" disabled>
                        Kirim Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

@endsection
