<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'role',
        'phone',
        'date_of_birth',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get primary address
     */
    public function primaryAddress()
    {
        return $this->hasOne(Address::class)->where('is_primary', true);
    }
    /**
     * Relasi ke Products melalui Favorites
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    /**
     * Relasi ke Products melalui Cart
     */
    public function cartProducts()
    {
        return $this->belongsToMany(Product::class, 'carts')->withPivot('quantity', 'price');
    }

    /**
     * Cek apakah user sudah mem-favorite product tertentu
     */
    public function hasFavorited($productId)
    {
        return $this->favorites()->where('product_id', $productId)->exists();
    }

    /**
     * Cek apakah user sudah menambahkan product ke cart
     */
    public function hasInCart($productId)
    {
        return $this->carts()->where('product_id', $productId)->exists();
    }

    /**
     * Hitung total items dalam cart
     */
    public function cartItemsCount()
    {
        return $this->carts()->sum('quantity');
    }

    public function getPrimaryAddressAttribute()
    {
        $address = $this->primaryAddress;
        return $address ? $address->formatted_address : null;
    }

}