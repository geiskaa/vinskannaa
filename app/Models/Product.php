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

    /**
     * Relasi ke Users melalui Favorites
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Hitung jumlah favorites
     */
    public function favoritesCount()
    {
        return $this->favorites_count ?? $this->favorites()->count();
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
