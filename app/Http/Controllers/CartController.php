<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function toggleCart(Request $request, Product $product)
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            Log::warning('Akses toggleCart tanpa login', [
                'product_id' => $product->id,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        Log::info('User mengakses toggleCart', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'event' => $request->input('event'),
        ]);

        $event = $request->input('event'); // 'icon' atau 'detail'

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        // Jika event berasal dari halaman detail → tambah atau update quantity
        if ($event === 'detail') {
            if ($product->stock !== null && $product->stock <= 0) {
                Log::notice('Produk habis stok saat ingin ditambahkan ke cart (detail)', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Produk sedang habis stok'
                ]);
            }

            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();

                Log::info('Quantity produk ditingkatkan di cart (detail)', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'new_quantity' => $cartItem->quantity,
                ]);

                $message = 'Jumlah produk diperbarui di keranjang';
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price
                ]);

                Log::info('Produk ditambahkan ke cart (detail)', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

                $message = 'Produk ditambahkan ke keranjang';
            }

            $inCart = true;
        } else {
            // Default toggle behavior (misalnya dari icon)
            if ($cartItem) {
                $cartItem->delete();

                Log::info('Produk dihapus dari cart (toggle)', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

                $message = 'Produk dihapus dari keranjang';
                $inCart = false;
            } else {
                if ($product->stock !== null && $product->stock <= 0) {
                    Log::notice('Produk habis stok saat toggle cart', [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Produk sedang habis stok'
                    ]);
                }

                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price
                ]);

                Log::info('Produk ditambahkan ke cart (toggle)', [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);

                $message = 'Produk ditambahkan ke keranjang';
                $inCart = true;
            }
        }

        $cartCount = $user->cartItemsCount();

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_cart' => $inCart,
            'cart_count' => $cartCount
        ]);
    }



    /**
     * Update quantity item di cart
     */
    public function updateQuantity(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);
        // Pastikan cart item milik user yang sedang login
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Cek stok produk
        if ($cart->product->stock !== null && $request->quantity > $cart->product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok yang tersedia'
            ]);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk diperbarui',
            'cart_count' => Auth::user()->cartItemsCount()
        ]);
    }

    /**
     * Hapus item dari cart
     */
    public function removeItem(Cart $cart)
    {
        // Pastikan cart item milik user yang sedang login
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang',
            'cart_count' => Auth::user()->cartItemsCount()
        ]);
    }

    /**
     * Tampilkan halaman cart
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart', compact('cartItems'));
    }

    /**
     * Clear semua items di cart
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
            'cart_count' => 0
        ]);
    }
}