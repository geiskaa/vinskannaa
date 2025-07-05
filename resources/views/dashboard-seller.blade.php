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
                        <span class="text-{{ $ordersGrowth >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                            {{ $ordersGrowth >= 0 ? '↗' : '↘' }} {{ abs($ordersGrowth) }}%
                        </span>
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
                        <span class="text-{{ $revenueGrowth >= 0 ? 'green' : 'red' }}-600 text-sm font-medium">
                            {{ $revenueGrowth >= 0 ? '↗' : '↘' }} {{ abs($revenueGrowth) }}%
                        </span>
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
                    <h3 class="text-lg font-semibold text-gray-900">Sales Graph</h3>
                    <div class="flex items-center space-x-2">
                        <!-- Filter Buttons -->
                        <div class="flex space-x-1">
                            <button onclick="changeFilter('weekly')"
                                class="filter-btn px-3 py-1 text-sm rounded {{ $filter === 'weekly' ? 'text-white bg-gray-800' : 'text-gray-600 hover:text-gray-900 border border-gray-300' }}">
                                WEEKLY
                            </button>
                            <button onclick="changeFilter('monthly')"
                                class="filter-btn px-3 py-1 text-sm rounded {{ $filter === 'monthly' ? 'text-white bg-gray-800' : 'text-gray-600 hover:text-gray-900 border border-gray-300' }}">
                                MONTHLY
                            </button>
                            <button onclick="changeFilter('yearly')"
                                class="filter-btn px-3 py-1 text-sm rounded {{ $filter === 'yearly' ? 'text-white bg-gray-800' : 'text-gray-600 hover:text-gray-900 border border-gray-300' }}">
                                YEARLY
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Period Navigator -->
                <div class="flex items-center justify-between mb-4">
                    <button onclick="navigatePeriod('previous')"
                        class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-2">
                        <span class="text-lg font-medium text-gray-700"
                            id="currentPeriod">{{ $navigationData['current'] }}</span>
                    </div>

                    <button onclick="navigatePeriod('next')"
                        class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
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
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                            ucfirst(str_replace('_', ' ', $order['status']));
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
        let currentChart = null;
        let currentFilter = '{{ $filter }}';
        let currentPeriod = '{{ $period }}';
        const navigationData = @json($navigationData);

        // Initialize chart
        function initChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chartData = @json($chartData);

            // Destroy existing chart if it exists
            if (currentChart) {
                currentChart.destroy();
            }

            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Sales',
                        data: chartData.data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp. ' + value.toLocaleString();
                                },
                                font: {
                                    size: 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Sales: Rp. ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Change filter function
        function changeFilter(filter) {
            if (filter === currentFilter) return;

            currentFilter = filter;

            // Update button states
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.className = btn.className.replace(/text-white bg-gray-800|text-gray-600.*border-gray-300/, '');
                if (btn.textContent.trim().toLowerCase() === filter) {
                    btn.className += ' text-white bg-gray-800';
                } else {
                    btn.className += ' text-gray-600 hover:text-gray-900 border border-gray-300';
                }
            });

            // Set default period based on filter
            if (filter === 'weekly') {
                currentPeriod = new Date().getFullYear() + '-' + String(new Date().getMonth() + 1).padStart(2, '0');
            } else if (filter === 'monthly') {
                currentPeriod = new Date().getFullYear() + '-' + String(new Date().getMonth() + 1).padStart(2, '0');
            } else if (filter === 'yearly') {
                currentPeriod = new Date().getFullYear().toString();
            }

            // Reload data
            loadChartData();
        }

        // Navigate period function
        function navigatePeriod(direction) {
            const current = new Date();

            if (currentFilter === 'weekly') {
                const [year, month] = currentPeriod.split('-');
                const date = new Date(year, month - 1, 1);

                if (direction === 'previous') {
                    date.setMonth(date.getMonth() - 1);
                } else {
                    date.setMonth(date.getMonth() + 1);
                }

                currentPeriod = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            } else if (currentFilter === 'monthly') {
                const [year, month] = currentPeriod.split('-');
                const date = new Date(year, month - 1, 1);

                if (direction === 'previous') {
                    date.setFullYear(date.getFullYear() - 1);
                } else {
                    date.setFullYear(date.getFullYear() + 1);
                }

                currentPeriod = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            } else if (currentFilter === 'yearly') {
                let year = parseInt(currentPeriod);

                if (direction === 'previous') {
                    year -= 1;
                } else {
                    year += 1;
                }

                currentPeriod = year.toString();
            }

            loadChartData();
        }

        // Load chart data via AJAX
        function loadChartData() {
            // Show loading state
            const canvas = document.getElementById('salesChart');
            const ctx = canvas.getContext('2d');

            // Clear canvas and show loading
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#6B7280';
            ctx.font = '16px system-ui';
            ctx.textAlign = 'center';
            ctx.fillText('Loading...', canvas.width / 2, canvas.height / 2);

            // Make AJAX request
            fetch(`{{ route('seller.dashboard') }}?filter=${currentFilter}&period=${currentPeriod}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Update chart data
                    updateChart(data.chartData);

                    // Update period display
                    updatePeriodDisplay(data.navigationData);

                    // Update stats if needed
                    if (data.stats) {
                        updateStats(data.stats);
                    }
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);

                    // Show error message
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.fillStyle = '#EF4444';
                    ctx.font = '16px system-ui';
                    ctx.textAlign = 'center';
                    ctx.fillText('Error loading data', canvas.width / 2, canvas.height / 2);
                });
        }

        // Update chart with new data
        function updateChart(chartData) {
            if (currentChart) {
                currentChart.data.labels = chartData.labels;
                currentChart.data.datasets[0].data = chartData.data;
                currentChart.update('active');
            } else {
                initChart();
            }
        }

        // Update period display
        function updatePeriodDisplay(navData) {
            const periodElement = document.getElementById('currentPeriod');
            if (periodElement && navData.current) {
                periodElement.textContent = navData.current;
            }
        }

        // Update stats (optional)
        function updateStats(stats) {
            // Update any stats that might change with different periods
            // This is optional and depends on your requirements
        }

        // Initialize chart on page load
        document.addEventListener('DOMContentLoaded', function() {
            initChart();
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                navigatePeriod('previous');
            } else if (e.key === 'ArrowRight') {
                navigatePeriod('next');
            }
        });
    </script>
@endpush
