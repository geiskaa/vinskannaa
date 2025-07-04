<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $products = Product::where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('seller.all-produk', compact('products'));
    }

    public function dashboard()
    {
        $productsCount = Product::where('seller_id', Auth::id())->count();
        $totalSales = Product::where('seller_id', Auth::id())->sum('price');


        return view('dashboard-seller', compact('productsCount', 'totalSales'));
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
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'section.required' => 'Bagian produk wajib dipilih',
            'category.required' => 'Kategori produk wajib dipilih',
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh kurang dari 0',
            'stock.required' => 'Stok produk wajib diisi',
            'stock.integer' => 'Stok harus berupa angka',
            'stock.min' => 'Stok tidak boleh kurang dari 0',
            'images.required' => 'Foto produk wajib diupload',
            'images.min' => 'Minimal 1 foto produk',
            'images.max' => 'Maksimal 5 foto produk',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        try {
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
                'seller_id' => Auth::id(),
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

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
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
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

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
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'section.required' => 'Bagian produk wajib dipilih',
            'category.required' => 'Kategori produk wajib dipilih',
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh kurang dari 0',
            'stock.required' => 'Stok produk wajib diisi',
            'stock.integer' => 'Stok harus berupa angka',
            'stock.min' => 'Stok tidak boleh kurang dari 0',
            'images.max' => 'Maksimal 5 foto produk',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        try {
            // Generate unique slug if name changed
            $slug = $product->slug;
            if ($request->name !== $product->name) {
                $newSlug = Str::slug($request->name);
                $originalSlug = $newSlug;
                $counter = 1;

                while (Product::where('slug', $newSlug)->where('id', '!=', $product->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $slug = $newSlug;
            }

            // Handle image uploads
            $imagePaths = json_decode($product->images, true) ?? [];

            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($imagePaths as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                // Upload new images
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    $imagePaths[] = $imagePath;
                }
            }

            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => $slug,
                'section' => $request->section,
                'category' => $request->category,
                'price' => $request->price,
                'stock' => $request->stock,
                'description' => $request->description,
                'images' => json_encode($imagePaths),
            ]);

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate produk. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product belongs to authenticated seller
        if ($product->seller_id !== Auth::id()) {
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

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus produk. Silakan coba lagi.');
        }
    }

}
