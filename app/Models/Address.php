<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'full_address',
        'city',
        'state',
        'postal_code',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk alamat primary
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        return $this->full_address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
    }

    /**
     * Set primary address (and unset others)
     */
    public function setPrimary()
    {
        // Set all user's addresses to non-primary
        $this->user->addresses()->update(['is_primary' => false]);

        // Set this address as primary
        $this->update(['is_primary' => true]);
    }
}