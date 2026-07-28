<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'subject', 'body', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'email' => '✉️',
            'sms' => '💬',
            'invoice' => '🧾',
            'certificate' => '🏆',
            default => '📄',
        };
    }
}
