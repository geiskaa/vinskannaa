<!-- Product Ratings Section -->
<div class="px-4    ">
    <div class="flex items-center justify-between ">
        <h3 class="text-xl font-bold text-gray-900">Rating & Ulasan</h3>
        <div class="flex items-center space-x-4">
            <!-- Rating Summary -->
            @if ($product->ratings()->exists())
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($product->averageRating()) ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="text-lg font-semibold">{{ number_format($product->averageRating(), 1) }}</span>
                    <span class="text-gray-600">({{ $product->ratings()->count() }} ulasan)</span>
                </div>
            @else
                <span class="text-gray-500">Belum ada ulasan</span>
            @endif
        </div>
    </div>

    <!-- Rating Distribution -->
    @if ($product->ratings()->exists())
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-4">Distribusi Rating</h4>
            <div class="space-y-2">
                @for ($star = 5; $star >= 1; $star--)
                    @php
                        $count = $product->ratings()->where('rating', $star)->count();
                        $total = $product->ratings()->count();
                        $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                    @endphp
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium w-8">{{ $star }}</span>
                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    <!-- Individual Reviews -->
    @if ($product->ratings()->exists())
        <div class="space-y-6">
            @foreach ($product->ratings()->with(['user', 'order'])->latest()->get() as $rating)
                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-lg">
                                    {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <h5 class="font-semibold text-gray-900">{{ $rating->user->name }}</h5>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $rating->created_at->format('d M Y') }}
                                </span>
                            </div>

                            <!-- Review Text (if exists) -->
                            @if ($rating->ulasan)
                                <p class="text-gray-700 mb-3">{{ $rating->ulasan }}</p>
                            @endif

                            <!-- Purchase Info -->
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pembeli Terverifikasi
                                </span>
                                @if ($rating->order)
                                    <span>Order: #{{ $rating->order->order_number ?? $rating->order->id }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Show More Button (if there are many reviews) -->
        @if ($product->ratings()->count() > 5)
            <div class="text-center mt-8">
                <button
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors duration-200">
                    Lihat Semua Ulasan ({{ $product->ratings()->count() }})
                </button>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-4">
            <div class="mx-auto w-24 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Ulasan</h4>
            <p class="text-gray-600">Jadilah yang pertama memberikan ulasan untuk produk ini!</p>
        </div>
    @endif
</div>
