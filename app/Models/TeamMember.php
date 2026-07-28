<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'bio', 'email', 'phone', 'photo', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return collect($words)->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
    }
}
