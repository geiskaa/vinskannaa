<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Str;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allProduk()
    {
        $products = Product::where('seller_id', Auth::guard('seller')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('seller.all-produk', compact('products'));
    }

    public function dashboard()
    {
        $sellerId = Auth::guard('seller')->id();

        $productsCount = Product::where('seller_id', $sellerId)->count();

        $baseOrderQuery = Order::whereHas('items.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        });

        \Log::info('Query dasar pesanan untuk seller', ['seller_id' => $sellerId]);

        $totalOrders = $baseOrderQuery->count();
        \Log::info('Jumlah total pesanan', ['total_orders' => $totalOrders]);

        $activeOrders = (clone $baseOrderQuery)->whereIn('order_status', ['menunggu_konfirmasi', 'diproses', 'dikirim'])->count();
        \Log::info('Jumlah pesanan aktif', ['active_orders' => $activeOrders]);

        $completedOrders = (clone $baseOrderQuery)->where('order_status', 'selesai')->count();
        \Log::info('Jumlah pesanan selesai', ['completed_orders' => $completedOrders]);

        $totalRevenue = OrderItem::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->whereHas('order', function ($query) {
            $query->where('order_status', 'selesai');
        })->sum(\DB::raw('quantity * price'));

        $bestSellers = Product::where('seller_id', $sellerId)
            ->withSum([
                'orderItems as total_quantity' => function ($query) {
                    $query->whereHas('order', function ($q) {
                        $q->where('order_status', 'selesai');
                    });
                }
            ], 'quantity')
            ->withSum([
                'orderItems as total_revenue' => function ($query) {
                    $query->whereHas('order', function ($q) {
                        $q->where('order_status', 'selesai');
                    });
                }
            ], \DB::raw('quantity * price'))
            ->having('total_quantity', '>', 0)
            ->orderBy('total_quantity', 'desc')
            ->limit(3)
            ->get(['id', 'name', 'price'])
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'price' => $product->price,
                    'total_sales' => $product->total_quantity ?? 0,
                    'total_revenue' => $product->total_revenue ?? 0
                ];
            });

        $recentOrders = Order::with(['user:id,name', 'items.product:id,name,seller_id'])
            ->whereHas('items.product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($order) use ($sellerId) {
                $sellerItems = $order->items->filter(function ($item) use ($sellerId) {
                    return $item->product->seller_id == $sellerId;
                });

                $amount = $sellerItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

                return [
                    'order_id' => $order->id,
                    'product_name' => $sellerItems->first()->product->name ?? 'Multiple Products',
                    'created_at' => $order->created_at,
                    'customer_name' => $order->user->name ?? 'Unknown',
                    'status' => $order->order_status,
                    'amount' => $amount
                ];
            });

        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now()->endOfMonth();

        $monthlySales = OrderItem::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->where('order_status', 'selesai')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })->selectRaw('MONTH(orders.created_at) as month, YEAR(orders.created_at) as year, SUM(order_items.quantity * order_items.price) as total')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        $chartData = [];
        $currentDate = now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $key = $currentDate->format('Y-m');
            $salesData = $monthlySales->get($key);
            $chartData[] = $salesData ? round($salesData->total) : 0;
            $currentDate->addMonth();
        }

        $currentPeriodStart = now()->subMonths(6)->startOfMonth();
        $currentPeriodEnd = now()->endOfMonth();
        $previousPeriodStart = now()->subMonths(12)->startOfMonth();
        $previousPeriodEnd = now()->subMonths(6)->endOfMonth();

        $previousTotalOrders = Order::whereHas('items.product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])->count();

        $ordersGrowth = $previousTotalOrders > 0
            ? round((($totalOrders - $previousTotalOrders) / $previousTotalOrders) * 100, 1)
            : 0;

        $previousRevenue = OrderItem::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->whereHas('order', function ($query) use ($previousPeriodStart, $previousPeriodEnd) {
            $query->where('order_status', 'selesai')
                ->whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd]);
        })->sum(\DB::raw('quantity * price'));

        $revenueGrowth = $previousRevenue > 0
            ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : 0;

        return view('dashboard-seller', compact(
            'productsCount',
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'totalRevenue',
            'bestSellers',
            'recentOrders',
            'chartData',
            'ordersGrowth',
            'revenueGrowth'
        ));
    }

    public function listPesanan(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 10);

        // Query builder untuk orders
        $query = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($status && $status !== 'all') {
            $query->where('order_status', $status);
        }

        // Filter berdasarkan tanggal jika ada
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }

        // Pagination
        $orders = $query->paginate($perPage);
        Log::info('ini order' . $orders);
        // Data untuk dropdown status (sesuaikan dengan yang ada di blade)
        $statusOptions = [
            'all' => 'All Orders',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'diproses' => 'Diproses',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return view('seller.pesanan-list', compact('orders', 'statusOptions'));
    }

    // Fungsi untuk konfirmasi pesanan
    public function konfirmasiPesanan(Request $request, $orderId)
    {
        try {
            \Log::info('Memulai proses konfirmasi pesanan', ['order_id' => $orderId]);

            $order = Order::findOrFail($orderId);
            \Log::info('Pesanan ditemukan', ['order' => $order]);

            // Update status menjadi dikonfirmasi
            $order->update([
                'order_status' => 'dikonfirmasi',
                'confirmed_at' => now()
            ]);

            Log::info('Status pesanan berhasil diperbarui', [
                'order_id' => $order->id,
                'order_status' => $order->order_status,
                'confirmed_at' => $order->confirmed_at
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengonfirmasi pesanan', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }


    // Fungsi untuk proses pesanan
    public function prosesPesanan(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Update status menjadi diproses
            $order->update([
                'order_status' => 'diproses',
                'processed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan sedang diproses'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Fungsi untuk kirim pesanan
    public function kirimPesanan(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Update status menjadi dikirim
            $order->update([
                'order_status' => 'dikirim',
                'shipped_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Fungsi untuk update status pesanan secara batch
    public function updateStatusBatch(Request $request)
    {
        try {
            $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id',
                'status' => 'required|in:menunggu_konfirmasi,dikonfirmasi,diproses,dikirim,selesai,dibatalkan'
            ]);

            $orderIds = $request->order_ids;
            $status = $request->status;

            // Update semua order yang dipilih
            Order::whereIn('id', $orderIds)->update([
                'order_status' => $status,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    // Fungsi untuk mendapatkan detail pesanan
    public function detailPesanan($orderId)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($orderId);

        return view('seller.pesanan-detail', compact('order'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seller.tambah-produk');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => ['required', Rule::in(['men', 'women', 'kids'])],
            'category' => [
                'required',
                Rule::in([
                    'kemeja',
                    'kaos',
                    'jaket',
                    'celana_panjang',
                    'celana_pendek',
                    'hoodie',
                    'dress',
                    'rok',
                    'sweater'
                ])
            ],
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            Log::info('Memulai proses tambah produk', [
                'seller_id' => Auth::guard('seller')->id(),
                'nama_produk' => $request->name,
            ]);

            // Generate unique slug
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    $imagePaths[] = $imagePath;
                }
            }

            // Create product
            $product = Product::create([
                'seller_id' => Auth::guard('seller')->id(),
                'name' => $request->name,
                'slug' => $slug,
                'section' => $request->section,
                'category' => $request->category,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
                'images' => json_encode($imagePaths),
                'ratings' => 0,
            ]);

            Log::info('Produk berhasil ditambahkan', [
                'product_id' => $product->id,
                'slug' => $slug,
            ]);

            return redirect()->route('seller.all-produk')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Gagal menambahkan produk', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan produk. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editProduk($id)
    {
        $product = Product::findOrFail($id);

        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.edit-produk', compact('product'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Memulai proses update produk', ['product_id' => $id, 'request' => $request->all()]);

        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|in:men,women,kids',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'removed_images.*' => 'nullable|string'
        ]);

        $product = Product::findOrFail($id);

        Log::info('Ditemukan produk', ['product' => $product]);

        // Update info dasar produk
        $product->update([
            'name' => $request->name,
            'section' => $request->section,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        Log::info('Data produk berhasil diperbarui', ['product_id' => $product->id]);

        $currentImages = $product->images ? json_decode($product->images, true) : [];

        // Hapus gambar yang dihapus user
        if ($request->has('removed_images') && $request->removed_images) {
            foreach ($request->removed_images as $removedImage) {
                Log::info('Menghapus gambar lama', ['path' => $removedImage]);

                if (Storage::exists('public/' . $removedImage)) {
                    Storage::delete('public/' . $removedImage);
                    Log::info('Gambar berhasil dihapus dari storage', ['path' => $removedImage]);
                } else {
                    Log::warning('Gambar tidak ditemukan di storage', ['path' => $removedImage]);
                }

                $currentImages = array_filter($currentImages, fn($img) => $img !== $removedImage);
            }
        }

        // Tambah gambar baru
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('products', 'public');
                $currentImages[] = $path;

                Log::info('Gambar baru ditambahkan', ['path' => $path]);
            }
        } else {
            Log::info('Tidak ada gambar baru yang diupload');
        }

        // Simpan semua gambar
        $product->update([
            'images' => json_encode(array_values($currentImages))
        ]);

        Log::info('Gambar produk diperbarui', ['product_id' => $product->id, 'images' => $currentImages]);

        return redirect()->route('seller.all-produk')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    protected function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'section' => 'required|in:men,women,kids',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'new_images' => 'nullable|array|max:10', // Max 10 new images
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB each
            'removed_images' => 'nullable|array',
            'removed_images.*' => 'string'
        ];
    }

    // Helper method untuk validasi total gambar
    protected function validateTotalImages($currentImages, $newImages, $removedImages)
    {
        $currentCount = count($currentImages);
        $removedCount = count($removedImages ?? []);
        $newCount = count($newImages ?? []);

        $totalAfterUpdate = $currentCount - $removedCount + $newCount;

        if ($totalAfterUpdate > 10) {
            throw new \Exception('Total gambar tidak boleh lebih dari 10');
        }

        if ($totalAfterUpdate < 1) {
            throw new \Exception('Produk harus memiliki minimal 1 gambar');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::guard('seller')->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete product images
            $imagePaths = json_decode($product->images, true) ?? [];
            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            // Delete product
            $product->delete();

            return redirect()->route('seller.all-produk')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus produk. Silakan coba lagi.');
        }
    }

}
