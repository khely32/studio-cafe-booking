<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class)->withPivot('quantity', 'price_at_time');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
