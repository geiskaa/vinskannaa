<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::guard('web')->user();

        // Ambil item keranjang beserta relasi produk
        $cartItems = $user->carts()->with('product')->get();

        // Hitung subtotal, pajak, dan total
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $tax = $subtotal * 0.11; // Pajak 11%
        $shipping = 15000; // Ongkir default
        $total = $subtotal + $tax + $shipping;

        // Kirim ke view
        return view('checkout', compact('user', 'cartItems', 'subtotal', 'tax', 'shipping', 'total'));

    }

    public function getSnapToken(Request $request)
    {
        try {
            // Validasi request
            $validated = $request->validate([
                'shipping_method' => 'required|in:regular,express,same_day', // Perbaiki: same_day bukan sameday
                'name' => 'required|string|max:255', // Perbaiki: name bukan full_name
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'shipping_cost' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'notes' => 'nullable|string',
            ]);

            // Set konfigurasi Midtrans
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Cek apakah ini checkout direct atau dari cart
            $checkoutData = session('checkout_data');
            $isDirectCheckout = $checkoutData && isset($checkoutData['type']) && $checkoutData['type'] === 'direct';

            $cartItems = collect();
            $subtotal = 0;

            if ($isDirectCheckout) {
                // Checkout direct - ambil dari session
                $cartItems = collect($checkoutData['items'])->map(function ($item) {
                    return (object) [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'], // Pastikan ada price
                        'total_price' => $item['total_price'],
                        'product' => (object) [
                            'id' => $item['product_id'],
                            'name' => $item['product_name'],
                            'price' => $item['price']
                        ]
                    ];
                });
                $subtotal = $checkoutData['subtotal'];
            } else {
                // Checkout dari cart - ambil dari database
                $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

                if ($cartItems->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Keranjang kosong'
                    ], 400);
                }

                // Hitung subtotal dari cart items
                $subtotal = $cartItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
            }

            // Hitung biaya tambahan
            $discount = $this->calculateDiscount($subtotal);
            $tax = $this->calculateTax($subtotal);
            $shippingCost = (float) $validated['shipping_cost'];
            $grossAmount = $subtotal - $discount + $tax + $shippingCost;

            // Gabungkan alamat lengkap
            $fullAddress = "{$validated['name']}, {$validated['phone']}, {$validated['address']}, {$validated['city']}, {$validated['province']}, {$validated['postal_code']}";

            // Buat Order ID unik
            $orderId = 'ORDER-' . time() . '-' . Auth::id();

            // Simpan order ke database
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_id' => $orderId,
                'status' => 'pending',
                'order_status' => 'menunggu_konfirmasi',
                'total_amount' => $grossAmount,
                'shipping_cost' => $shippingCost,
                'shipping_method' => $validated['shipping_method'],
                'shipping_address' => $fullAddress,
                'payment_type' => null,
                'payment_token' => null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Simpan order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id ?? $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Buat item details untuk Midtrans
            $itemDetails = [];

            foreach ($cartItems as $item) {
                $itemDetails[] = [
                    'id' => 'PROD-' . ($item->product_id ?? $item->product->id),
                    'price' => (int) $item->product->price,
                    'quantity' => (int) $item->quantity,
                    'name' => substr($item->product->name, 0, 50),
                ];
            }

            // Tambahkan item untuk diskon jika ada
            if ($discount > 0) {
                $itemDetails[] = [
                    'id' => 'DISCOUNT',
                    'price' => -(int) $discount,
                    'quantity' => 1,
                    'name' => 'Diskon Pembelian',
                ];
            }

            // Tambahkan item untuk pajak
            if ($tax > 0) {
                $itemDetails[] = [
                    'id' => 'TAX',
                    'price' => (int) $tax,
                    'quantity' => 1,
                    'name' => 'Pajak (11%)',
                ];
            }

            // Tambahkan item untuk ongkir
            if ($shippingCost > 0) {
                $shippingMethodNames = [
                    'regular' => 'Pengiriman Regular',
                    'express' => 'Pengiriman Express',
                    'same_day' => 'Pengiriman Same Day'
                ];

                $itemDetails[] = [
                    'id' => 'SHIPPING',
                    'price' => (int) $shippingCost,
                    'quantity' => 1,
                    'name' => $shippingMethodNames[$validated['shipping_method']] ?? 'Ongkos Kirim',
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $grossAmount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $validated['name'],
                    'email' => Auth::user()->email,
                    'phone' => $validated['phone'],
                    'billing_address' => [
                        'first_name' => $validated['name'],
                        'address' => $validated['address'],
                        'city' => $validated['city'],
                        'postal_code' => $validated['postal_code'],
                        'country_code' => 'IDN'
                    ],
                    'shipping_address' => [
                        'first_name' => $validated['name'],
                        'address' => $validated['address'],
                        'city' => $validated['city'],
                        'postal_code' => $validated['postal_code'],
                        'country_code' => 'IDN'
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update order dengan token
            $order->update(['payment_token' => $snapToken]);

            // Hapus cart items jika bukan direct checkout
            if (!$isDirectCheckout) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                // Hapus session checkout data untuk direct checkout
                session()->forget('checkout_data');
            }

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken, // Perbaiki: snap_token bukan token
                'order_id' => $order->id, // Return database ID bukan order_id string
                'checkout_type' => $isDirectCheckout ? 'direct' : 'cart',
                'breakdown' => [
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $tax,
                    'shipping_cost' => $shippingCost,
                    'total' => $grossAmount
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculateDiscount($subtotal)
    {
        if ($subtotal >= 5000000) {
            return 500000; // Diskon 500k untuk pembelian >= 5 juta
        }
        return 0;
    }

    private function calculateTax($subtotal)
    {
        return $subtotal * 0.11;
    }

    private function getShippingCost($method)
    {
        $costs = [
            'regular' => 15000,
            'express' => 25000,
            'sameday' => 35000
        ];

        return $costs[$method] ?? 25000;
    }

}
