@extends('layouts.seller')

@section('title', 'Ulasan List - ThriftMart Seller')
@section('page-title', 'Ulasan List')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Ulasan Semua Produk</h2>
                <nav class="text-sm text-gray-500 mt-1">
                    <span>Home</span>
                    <span class="mx-1">></span>
                    <span>Ulasan List</span>
                </nav>
            </div>

            <!-- Filter Section -->
            <div class="flex items-center space-x-4">
                <!-- Date Range Filter -->
                <form method="GET" action="{{ route('seller.list-ulasan') }}" class="flex items-center space-x-2"
                    id="filter-form">
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

                    <!-- Rating Filter -->
                    <div class="relative" x-data="{ open: false }">
                        <button type="button" @click="open = !open"
                            class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $ratingOptions[request('rating', 'all')] }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            @foreach ($ratingOptions as $key => $label)
                                <button type="button" onclick="setRatingFilter('{{ $key }}')"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request('rating', 'all') == $key ? 'bg-gray-100' : '' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Hidden input for rating -->
                    <input type="hidden" name="rating" value="{{ request('rating', 'all') }}" id="rating-filter">

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Filter
                    </button>

                    <!-- Reset Filter Button -->
                    <a href="{{ route('seller.list-ulasan') }}"
                        class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Ulasan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalReviews ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rating Rata-rata</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($averageRating ?? 0, 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ulasan Bulan Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $monthlyReviews ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Ulasan</h3>
                    <button class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>

                @if ($reviews->count() > 0)
                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @foreach ($reviews as $review)
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <!-- Review Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                {{ $review->created_at->format('M jS, Y - H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <!-- Rating Stars -->
                                        <div class="flex items-center space-x-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="flex items-center space-x-3 mb-4 p-3 bg-gray-50 rounded-lg">
                                    @php
                                        $image = $review->product->images[0] ?? null;
                                    @endphp

                                    @if ($image)
                                        <img src="{{ Str::startsWith($image, ['http://', 'https://']) ? $image : asset('storage/' . $image) }}"
                                            alt="{{ $review->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <span class="text-sm text-gray-500">No image</span>
                                    @endif

                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $review->product->name }}</h5>
                                        <p class="text-sm text-gray-600">Rp
                                            {{ number_format($review->product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Review Content -->
                                <div class="mb-4">
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>

                                <!-- Review Images (if any) -->
                                @if ($review->images && $review->images->count() > 0)
                                    <div class="mb-4">
                                        <div class="flex space-x-2">
                                            @foreach ($review->images as $image)
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="Review image"
                                                    class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                                    onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}')">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif


                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-700">
                            Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }}
                            entries
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $reviews->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada ulasan untuk produk Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Balas Ulasan</h3>
                <form id="replyForm" method="POST">
                    @csrf
                    <textarea name="reply" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tulis balasan Anda..."></textarea>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeReplyModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kirim Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative">
                <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="Review image" class="max-w-full max-h-screen rounded-lg">
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Function to set rating filter
            function setRatingFilter(rating) {
                const input = document.getElementById('rating-filter');
                if (input) {
                    input.value = rating;
                    const form = document.getElementById('filter-form');
                    if (form) {
                        form.submit();
                    }
                }
            }

            // Reply modal functions
            function openReplyModal(reviewId) {
                const modal = document.getElementById('replyModal');
                const form = document.getElementById('replyForm');
                form.action = `/seller/list-ulasan/${reviewId}/reply`;
                modal.classList.remove('hidden');
            }

            function closeReplyModal() {
                const modal = document.getElementById('replyModal');
                modal.classList.add('hidden');
            }

            // Image modal functions
            function openImageModal(imageSrc) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = imageSrc;
                modal.classList.remove('hidden');
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
            }

            // Handle reply form submission
            document.getElementById('replyForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = this.action;

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Balasan berhasil dikirim!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim balasan.');
                    });
            });

            // Close modals when clicking outside
            document.getElementById('replyModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeReplyModal();
                }
            });

            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });
        </script>
    @endpush
@endsection
