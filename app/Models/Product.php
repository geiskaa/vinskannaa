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

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Orders melalui OrderItems
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
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

    /**
     * Hitung rata-rata rating
     */
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
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