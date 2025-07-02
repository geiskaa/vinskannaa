<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 5;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;

        // Mulai query produk
        $query = Product::query();

        // Filter berdasarkan section jika disediakan dan bukan 'all'
        $section = $request->get('section');
        $search = $request->get('search', '');

        if ($section && $section !== 'all' && in_array($section, ['men', 'women', 'kids'])) {
            $query->where('section', $section);
        }

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('category', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Hitung total produk setelah filter
        $totalProducts = $query->count();
        Log::info("Total produk ditemukan setelah filter: {$totalProducts}, Search: '{$search}', Section: '{$section}', Page: {$page}");

        // Ambil produk untuk halaman saat ini
        $products = $query->latest()
            ->skip($offset)
            ->take($perPage)
            ->get();

        $hasMore = ($offset + $perPage) < $totalProducts;
        $currentPage = $page;

        // Log user info
        $userId = auth('web')->check() ? auth('web')->id() : 'Guest';
        Log::info("User ID {$userId} membuka dashboard, halaman {$page}, section: {$section}, total: {$totalProducts}, hasMore: " . ($hasMore ? 'true' : 'false'));

        // Set user-specific properties
        foreach ($products as $product) {
            if (auth('web')->check()) {
                $user = auth('web')->user();

                // Check if product is favorited
                $product->is_favorited = $user->favorites()
                    ->where('product_id', $product->id)
                    ->exists();

                // Check if product is in cart
                $product->in_cart = $user->carts()
                    ->where('product_id', $product->id)
                    ->exists();
            } else {
                $product->is_favorited = false;
                $product->in_cart = false;
            }

            // Log the product status AFTER setting the properties
            Log::info("Produk ID {$product->id}: Favorited = " .
                var_export($product->is_favorited, true) .
                ", In Cart = " .
                var_export($product->in_cart, true));
        }

        // Jika request AJAX, kembalikan partial view + JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'products' => $products,
                'hasMore' => $hasMore,
                'currentPage' => $currentPage,
                'search' => $search,
                'section' => $section,
                'total' => $totalProducts,
                'showing' => $products->count(),
                'html' => view('partials.product-grid', compact('products'))->render()
            ]);
        }

        return view('dashboard', compact('products', 'hasMore', 'currentPage'));
    }

    public function toggle(Request $request, Product $product)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            // Jika sudah di-favorite, hapus dari favorites
            $favorite->delete();
            $isFavorited = false;
            $message = 'Produk dihapus dari favorites';
        } else {
            // Jika belum di-favorite, tambahkan ke favorites
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $isFavorited = true;
            $message = 'Produk ditambahkan ke favorites';
        }

        // Log after the toggle action
        Log::info("Produk: {$product->name} (ID: {$product->id}) | Action: " .
            ($isFavorited ? 'Added to' : 'Removed from') . " favorites");

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message,
            'favorites_count' => $product->favoritesCount()
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->get('q', '');
        $section = $request->get('section', 'all');
        $perPage = $request->get('per_page', 15);

        if (empty($search)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required',
                'data' => []
            ]);
        }

        $query = Product::query();

        // Search in multiple fields
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('category', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orWhere('brand', 'LIKE', '%' . $search . '%'); // if you have brand field
        });

        // Apply section filter
        if ($section !== 'all') {
            $query->where('section', $section);
        }

        // Order by relevance (name matches first, then category, then description)
        $query->orderByRaw("
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN category LIKE ? THEN 2
                WHEN description LIKE ? THEN 3
                ELSE 4
            END,
            created_at DESC
        ", ["%{$search}%", "%{$search}%", "%{$search}%"]);

        $products = $query->paginate($perPage);

        // Add user-specific data if authenticated
        if (Auth::check()) {
            $user = Auth::user();
            $favoriteProductIds = $user->favorites()->pluck('product_id')->toArray();
            $cartProductIds = $user->cartItems()->pluck('product_id')->toArray();

            foreach ($products as $product) {
                $product->is_favorited = in_array($product->id, $favoriteProductIds);
                $product->in_cart = in_array($product->id, $cartProductIds);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'has_more' => $products->hasMorePages(),
            ],
            'search' => $search,
            'section' => $section,
        ]);
    }
}