<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10; // Jumlah produk per halaman
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $products = Product::latest()
            ->skip($offset)
            ->take($perPage)
            ->get();

        $totalProducts = Product::count();
        $hasMore = ($offset + $perPage) < $totalProducts;
        $currentPage = $page;

        // Jika user login, load status favorites untuk setiap produk
        if (auth('user')->check()) {
            $favoriteIds = auth('user')->user()->favorites()->pluck('product_id')->toArray();
            foreach ($products as $product) {
                $product->is_favorited = in_array($product->id, $favoriteIds);
            }
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

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'message' => $message,
            'favorites_count' => $product->favoritesCount()
        ]);
    }

}
