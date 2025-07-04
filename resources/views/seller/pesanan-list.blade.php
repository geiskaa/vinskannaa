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

            <!-- Date Range Filter -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-gray-200">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Feb 16,2022 - Feb 20,2022</span>
                </div>

                <!-- Status Filter -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                        <span class="text-sm font-medium text-gray-700">Change Status</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Orders</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pending</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Confirmed</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Processing</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Shipped</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delivered</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cancelled</a>
                    </div>
                </div>
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

                <!-- Orders Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
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
                            <!-- Order 1 -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Baju Hitam</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25426</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 8th, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            K
                                        </div>
                                        <span class="text-sm text-gray-900">Kavin</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        Delivered
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs text-gray-500">No action needed</span>
                                </td>
                            </tr>

                            <!-- Order 2 - Pending Confirmation -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Celana Jeans</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25425</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 7th, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            K
                                        </div>
                                        <span class="text-sm text-gray-900">Komael</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span>
                                        Pending
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                        Konfirmasi
                                    </button>
                                </td>
                            </tr>

                            <!-- Order 3 - Need Processing -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Celana Jeans</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25424</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 6th, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            N
                                        </div>
                                        <span class="text-sm text-gray-900">Nikhil</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-1"></span>
                                        Confirmed
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <button
                                        class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                        Perlu Diproses
                                    </button>
                                </td>
                            </tr>

                            <!-- Order 4 - Cancelled -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Baju Hitam</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25423</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 5th, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            S
                                        </div>
                                        <span class="text-sm text-gray-900">Shivam</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                        Cancelled
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-red-600 font-medium">Refunded</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs text-gray-500">Cancelled</span>
                                </td>
                            </tr>

                            <!-- Order 5 - Need Shipping -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Celana Jeans</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25422</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 4th, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            S
                                        </div>
                                        <span class="text-sm text-gray-900">Shadab</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-1"></span>
                                        Processing
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <button
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">
                                        Perlu Dikirim
                                    </button>
                                </td>
                            </tr>

                            <!-- Order 6 - Delivered -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Baju Hitam</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25421</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 2nd, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            Y
                                        </div>
                                        <span class="text-sm text-gray-900">Yogesh</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        Delivered
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs text-gray-500">Completed</span>
                                </td>
                            </tr>

                            <!-- Order 7 - Cancelled -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Celana Jeans</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25423</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 1st, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            S
                                        </div>
                                        <span class="text-sm text-gray-900">Sunita</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                        Cancelled
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-red-600 font-medium">Refunded</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs text-gray-500">Cancelled</span>
                                </td>
                            </tr>

                            <!-- Order 8 - Delivered -->
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-900">Baju Hitam</td>
                                <td class="py-4 px-4 text-sm text-gray-900">#25421</td>
                                <td class="py-4 px-4 text-sm text-gray-600">Nov 1st, 2023</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <div
                                            class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                            P
                                        </div>
                                        <span class="text-sm text-gray-900">Priyanka</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        Delivered
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-sm font-medium text-gray-900">₹200.00</td>
                                <td class="py-4 px-4 text-sm text-green-600 font-medium">Success</td>
                                <td class="py-4 px-4">
                                    <span class="text-xs text-gray-500">Completed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between mt-6">
                    <div class="text-sm text-gray-700">
                        Showing 1 to 8 of 8 entries
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 text-sm font-medium text-white bg-gray-900 rounded-md">1</button>
                        <button
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                        <button
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                        <button
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">4</button>
                        <span class="px-3 py-1 text-sm text-gray-500">...</span>
                        <button
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">10</button>
                        <button
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            NEXT
                            <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Modals -->
    <div x-data="{ showModal: false, modalType: '', orderId: '' }">
        <!-- Confirmation Modal -->
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Aksi</h3>
                    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin melakukan aksi ini pada order <span
                            x-text="orderId"></span>?</p>

                    <div class="flex justify-end space-x-3">
                        <button @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Batal
                        </button>
                        <button @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Add event listeners for action buttons
            document.addEventListener('DOMContentLoaded', function() {
                // Handle confirmation buttons
                document.querySelectorAll('[data-action="confirm"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-order-id');
                        // Add your confirmation logic here
                        console.log('Confirming order:', orderId);
                    });
                });

                // Handle process buttons
                document.querySelectorAll('[data-action="process"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-order-id');
                        // Add your processing logic here
                        console.log('Processing order:', orderId);
                    });
                });

                // Handle ship buttons
                document.querySelectorAll('[data-action="ship"]').forEach(button => {
                    button.addEventListener('click', function() {
                        const orderId = this.getAttribute('data-order-id');
                        // Add your shipping logic here
                        console.log('Shipping order:', orderId);
                    });
                });
            });
        </script>
    @endpush
@endsection
