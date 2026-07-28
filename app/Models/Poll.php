<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'description', 'is_active', 'is_closed'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
    ];

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('sort_order');
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->options->sum('votes');
    }

    public function hasUserVoted(string $ip): bool
    {
        return $this->votes()->where('voter_ip', $ip)->exists();
    }

    public function getResults(): array
    {
        $total = $this->total_votes;
        return $this->options->map(function ($option) use ($total) {
            $percent = $total > 0 ? round(($option->votes / $total) * 100, 1) : 0;
            return [
                'id' => $option->id,
                'text' => $option->option_text,
                'votes' => $option->votes,
                'percent' => $percent,
            ];
        })->toArray();
    }
}
