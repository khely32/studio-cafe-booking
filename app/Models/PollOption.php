<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id', 'option_text', 'votes', 'sort_order'];

    protected $casts = [
        'votes' => 'integer',
        'sort_order' => 'integer',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }
}
