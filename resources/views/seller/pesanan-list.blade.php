@extends('layouts.seller')

@section('title', 'Order List - ThriftMart Seller')
@section('page-title', 'Order List')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Orders List</h2>
                <nav class="text-sm text-gray-500 mt-1">
                    <span>Home</span>
                    <span class="mx-1">></span>
                    <span>Order List</span>
                </nav>
            </div>

            <!-- Filter Section -->
            <div class="flex items-center space-x-4">
                <!-- Date Range Filter -->
                <form method="GET" action="{{ route('seller.list-pesanan') }}" class="flex items-center space-x-2">
                    <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-gray-200">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="text-sm border-0 p-0 focus:ring-0" placeholder="From">
                        <span class="text-gray-500">-</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="text-sm border-0 p-0 focus:ring-0" placeholder="To">
                    </div>

                    <!-- Status Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open"
                            class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $statusOptions[request('status', 'all')] }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            @foreach ($statusOptions as $key => $label)
                                <button type="button" onclick="setStatusFilter('{{ $key }}')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('status', 'all') == $key ? 'bg-gray-100' : '' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hidden input for status -->
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}" id="status-filter">

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Filter
                    </button>

                    <!-- Reset Filter Button -->
                    <a href="{{ route('seller.list-pesanan') }}"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Recent Purchases Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Purchases</h3>
                    <button class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>

                @if ($orders->count() > 0)
                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Product</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Order ID</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Date</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Customer Name</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Status</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Amount</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Payment Status</th>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-4 text-sm text-gray-900">
                                            {{ $order->items->first()->product->name ?? 'N/A' }}
                                            @if ($order->items->count() > 1)
                                                <span class="text-xs text-gray-500">(+{{ $order->items->count() - 1 }}
                                                    more)</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-900">#{{ $order->order_id }}</td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            {{ $order->created_at->format('M jS, Y') }}</td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="w-8 h-8 bg-{{ ['blue', 'green', 'purple', 'orange', 'red', 'teal', 'pink'][rand(0, 6)] }}-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                </div>
                                                <span class="text-sm text-gray-900">{{ $order->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @php
                                                $statusConfig = [
                                                    'menunggu_konfirmasi' => [
                                                        'class' => 'bg-yellow-100 text-yellow-800',
                                                        'dot' => 'bg-yellow-500',
                                                        'text' => 'Menunggu Konfirmasi',
                                                    ],
                                                    'dikonfirmasi' => [
                                                        'class' => 'bg-blue-100 text-blue-800',
                                                        'dot' => 'bg-blue-500',
                                                        'text' => 'Dikonfirmasi',
                                                    ],
                                                    'diproses' => [
                                                        'class' => 'bg-purple-100 text-purple-800',
                                                        'dot' => 'bg-purple-500',
                                                        'text' => 'Diproses',
                                                    ],
                                                    'dikirim' => [
                                                        'class' => 'bg-indigo-100 text-indigo-800',
                                                        'dot' => 'bg-indigo-500',
                                                        'text' => 'Dikirim',
                                                    ],
                                                    'selesai' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'dot' => 'bg-green-500',
                                                        'text' => 'Selesai',
                                                    ],
                                                    'dibatalkan' => [
                                                        'class' => 'bg-red-100 text-red-800',
                                                        'dot' => 'bg-red-500',
                                                        'text' => 'Dibatalkan',
                                                    ],
                                                ];
                                                $config =
                                                    $statusConfig[$order->order_status] ??
                                                    $statusConfig['menunggu_konfirmasi'];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                                <span class="w-2 h-2 {{ $config['dot'] }} rounded-full mr-1"></span>
                                                {{ $config['text'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-sm font-medium text-gray-900">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="py-4 px-4 text-sm {{ $order->paid_at ? 'text-green-600' : 'text-red-600' }} font-medium">
                                            {{ $order->paid_at ? 'Success' : 'Pending' }}
                                        </td>
                                        <td class="py-4 px-4">
                                            @if ($order->order_status == 'menunggu_konfirmasi')
                                                <button onclick="updateOrderStatus({{ $order->id }}, 'konfirmasi')"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Konfirmasi
                                                </button>
                                            @elseif($order->order_status == 'dikonfirmasi')
                                                <button onclick="updateOrderStatus({{ $order->id }}, 'proses')"
                                                    class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Proses
                                                </button>
                                            @elseif($order->order_status == 'diproses')
                                                <button onclick="updateOrderStatus({{ $order->id }}, 'kirim')"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                                    Kirim
                                                </button>
                                            @elseif($order->order_status == 'dikirim')
                                                <span class="text-xs text-gray-500">Menunggu Konfirmasi Customer</span>
                                            @elseif($order->order_status == 'selesai')
                                                <span class="text-xs text-gray-500">Completed</span>
                                            @elseif($order->order_status == 'dibatalkan')
                                                <span class="text-xs text-gray-500">Cancelled</span>
                                            @else
                                                <span class="text-xs text-gray-500">No action needed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-700">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                            entries
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No orders found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Function to update order status
            function updateOrderStatus(orderId, action) {
                console.log(action)
                const actionMap = {
                    'konfirmasi': 'konfirmasi',
                    'proses': 'proses',
                    'kirim': 'kirim'
                };

                const url = `/seller/list-pesanan/${orderId}/${actionMap[action]}`;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the order status.');
                    });
            }

            // Function to set status filter
            function setStatusFilter(status) {
                document.getElementById('status-filter').value = status;
            }

            // Select all checkbox functionality
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.order-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        </script>
    @endpush
@endsection
