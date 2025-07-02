<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'order_status',
        'total_amount',
        'shipping_cost',
        'tax_cost',
        'shipping_method',
        'shipping_address',
        'payment_type',
        'payment_token',
        'notes',
        'paid_at',
        'completed_at',
        'canceled_at',
        'confirmed_at',
        'processed_at',
        'dikirim_at',
        'cancel_reason',
    ];

    protected $dates = [
        'paid_at',
        'completed_at',
        'canceled_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\ProductRating::class);
    }

}
