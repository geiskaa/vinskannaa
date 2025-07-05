@extends('layouts.seller')

@section('title', 'Dashboard - ThriftMart')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Compared to last period</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 text-sm font-medium">↗ {{ $ordersGrowth }}%</span>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($activeOrders) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pending & Processing</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-yellow-600 text-sm font-medium">⏳ Active</span>
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($completedOrders) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Successfully delivered</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 text-sm font-medium">✓ Done</span>
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">Rp. {{ number_format($totalRevenue) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Compared to last period</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 text-sm font-medium">↗ {{ $revenueGrowth }}%</span>
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and Best Sellers -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Sales Chart -->
            <div class="xl:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Sale Graph</h3>
                    <div class="flex space-x-1">
                        <button
                            class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded">WEEKLY</button>
                        <button class="px-3 py-1 text-sm text-white bg-gray-800 rounded">MONTHLY</button>
                        <button
                            class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 border border-gray-300 rounded">YEARLY</button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Best Sellers -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Best Sellers</h3>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    @forelse($bestSellers as $product)
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-900 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                                <p class="text-sm text-gray-500">Base Price: Rp. {{ number_format($product['price'], 2) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Rp. {{ number_format($product['total_revenue'], 2) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $product['total_sales'] }} sales</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No sales data available</p>
                        </div>
                    @endforelse
                </div>
                <div class="mt-6">
                    <a href="{{ route('seller.list-pesanan') }}"
                        class="w-full bg-gray-800 text-white py-2 px-4 rounded-lg hover:bg-gray-900 transition-colors text-center block">
                        VIEW ORDERS
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <a href="{{ route('seller.list-pesanan') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $order['product_name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    #{{ $order['order_id'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order['created_at'])->format('M jS, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                            {{ strtoupper(substr($order['customer_name'], 0, 1)) }}
                                        </div>
                                        <span class="ml-2 text-sm text-gray-900">{{ $order['customer_name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
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

                                        $statusClass = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                                        $statusLabel =
                                            $statusLabels[$order['status']] ??
                                            ucfirst(str_replace('_', ' ', $order->order_status));

                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp. {{ number_format($order['amount'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No recent orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between text-sm text-gray-500 pt-4">
            <p>© 2023 - ThriftMart Dashboard</p>
            <div class="flex space-x-4">
                <a href="#" class="hover:text-gray-700">About</a>
                <a href="#" class="hover:text-gray-700">Careers</a>
                <a href="#" class="hover:text-gray-700">Policy</a>
                <a href="#" class="hover:text-gray-700">Contact</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
                datasets: [{
                    label: 'Sales',
                    data: chartData,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp. ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
