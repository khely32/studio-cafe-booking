<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
        'max_pax',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'max_pax' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_minutes >= 60) {
            $hours = floor($this->duration_minutes / 60);
            $mins = $this->duration_minutes % 60;
            return $mins > 0 ? "{$hours} hour {$mins} min" : Str::plural('hour', $hours);
        }
        return "{$this->duration_minutes} minutes";
    }
}
