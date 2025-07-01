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
        $query = Product::latest();

        $products = $query->skip($offset)
            ->take($perPage)
            ->get();

        $totalProducts = Product::count();
        $hasMore = ($offset + $perPage) < $totalProducts;
        $currentPage = $page;

        // Log user info
        $userId = auth('web')->check() ? auth('web')->id() : 'Guest';
        Log::info("User ID {$userId} membuka dashboard, halaman {$page}");

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

        // If this is an AJAX request, return JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'products' => $products,
                'hasMore' => $hasMore,
                'currentPage' => $currentPage,
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
}