@extends('layouts.app')

@section('title', 'Detail Pesanan - ThriftHub')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('pesananSaya') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Pesanan Saya
            </a>
        </div>

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_id }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusClasses = [
                                'menunggu_konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                'dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                'diproses' => 'bg-indigo-100 text-indigo-800',
                                'dikirim' => 'bg-purple-100 text-purple-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'dibatalkan' => 'bg-red-100 text-red-800',
                            ];

                            $statusLabels = [
                                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                'dikonfirmasi' => 'Dikonfirmasi',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ];

                            $statusClass = $statusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                            $statusLabel =
                                $statusLabels[$order->order_status] ??
                                ucfirst(str_replace('_', ' ', $order->order_status));
                        @endphp

                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                </div>
            </div>

            <!-- Order Timeline -->
            <div class="px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if ($order->confirmed_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Dikonfirmasi</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->confirmed_at)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($order->processed_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Diproses</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->processed_at)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($order->dikirim_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Dikirim</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->dikirim_at)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($order->completed_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Selesai</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($order->canceled_at)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Pesanan Dibatalkan</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->canceled_at)->format('d M Y, H:i') }}</p>
                                @if ($order->cancel_reason)
                                    <p class="text-xs text-gray-600 mt-1">Alasan: {{ $order->cancel_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Produk yang Dipesan</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
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
                                <h4 class="text-lg font-medium text-gray-900">
                                    {{ $item->product->name ?? 'Produk tidak ditemukan' }}</h4>
                                @if ($item->product && $item->product->category)
                                    <p class="text-sm text-gray-500">{{ $item->product->category }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Ringkasan Pesanan</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="text-gray-900">Rp
                            {{ number_format($order->items->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}</span>
                    </div>

                    @if ($order->shipping_cost > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Ongkos Kirim ({{ $order->shipping_method }})</span>
                            <span class="text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    @endif


                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak</span>
                        <span class="text-gray-900">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>


                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-semibold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        @if ($order->shipping_address)
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Alamat Pengiriman</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-700">{{ $order->shipping_address }}</p>
                    @if ($order->shipping_method)
                        <p class="text-sm text-gray-500 mt-2">Metode Pengiriman: {{ $order->shipping_method }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Pembayaran</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="text-gray-900">{{ $order->payment_type ?? 'Belum dipilih' }}</span>
                    </div>
                    @if ($order->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pembayaran</span>
                            <span
                                class="text-gray-900">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="text-gray-900">
                            @if ($order->paid_at)
                                <span class="text-green-600">Sudah Dibayar</span>
                            @else
                                <span class="text-red-600">Belum Dibayar</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if ($order->notes)
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Catatan</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-between">
            <a href="{{ route('pesananSaya') }}"
                class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Kembali
            </a>

            <div class="flex space-x-3">
                @if ($order->order_status == 'menunggu_konfirmasi')
                    <button onclick="cancelOrder('{{ $order->id }}')"
                        class="px-6 py-3 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-md hover:bg-red-50">
                        Batalkan Pesanan
                    </button>
                @endif

                @if (in_array($order->order_status, ['selesai']) && !$order->ratings->count())
                    <a href="{{ route('orders.rate', $order->id) }}"
                        class="px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Beri Rating
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

@section('scripts')
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
            toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

            document.getElementById('toast-container').appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function cancelOrder(orderId) {
            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                fetch(`/pesanan-saya/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Pesanan berhasil dibatalkan', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showToast('Gagal membatalkan pesanan', 'error');
                        }
                    })
                    .catch(error => {
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }
    </script>
@endsection
