<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_ref',
        'service_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'num_pax',
        'booking_date',
        'booking_time',
        'special_requests',
        'internal_notes',
        'payment_reminder_sent_at',
        'total_amount',
        'amount_paid',
        'payment_method',
        'payment_status',
        'status',
        'strip_payment_intent_id',
        'agreed_to_policy',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'num_pax' => 'integer',
        'agreed_to_policy' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_ref)) {
                do {
                    $ref = strtoupper(Str::random(4) . '-' . Str::random(4));
                } while (static::where('booking_ref', $ref)->exists());

                $booking->booking_ref = $ref;
            }
        });
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class)->withPivot('quantity', 'price_at_time');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            'no_show' => 'secondary',
            default => 'secondary',
        };
    }
}
