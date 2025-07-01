<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'section',
        'category',
        'price',
        'description',
        'image',
        'stock',
        'ratings',
        'images',
        'seller_id',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'ratings' => 'float',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi ke Users melalui Favorites
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Relasi ke Users melalui Carts
     */
    public function cartedByUsers()
    {
        return $this->belongsToMany(User::class, 'carts')->withPivot('quantity', 'price');
    }

    /**
     * Hitung jumlah favorites
     */
    public function favoritesCount()
    {
        return $this->favorites_count ?? $this->favorites()->count();
    }

    /**
     * Hitung jumlah total di cart dari semua user
     */
    public function cartQuantity()
    {
        return $this->carts()->sum('quantity');
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}