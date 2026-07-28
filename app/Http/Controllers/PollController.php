<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollVote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function show(Poll $poll)
    {
        if (!$poll->is_active) {
            abort(404);
        }
        return view('polls.vote', compact('poll'));
    }

    public function vote(Request $request, Poll $poll)
    {
        if (!$poll->is_active || $poll->is_closed) {
            return redirect()->back()->with('error', 'This poll is no longer accepting votes.');
        }

        $request->validate([
            'options' => 'required|array|min:1',
        ]);

        $selectedIds = $request->input('options');
        $validIds = $poll->options->pluck('id')->toArray();

        foreach ($selectedIds as $optId) {
            if (!in_array($optId, $validIds)) {
                return redirect()->back()->with('error', 'Invalid option selected.');
            }
        }

        $ip = $request->ip();
        $existing = PollVote::where('poll_id', $poll->id)
            ->where('ip_address', $ip)
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already voted in this poll.');
        }

        $name = $request->input('voter_name', 'Anonymous');

        foreach ($selectedIds as $optId) {
            PollVote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $optId,
                'voter_name' => $name,
                'ip_address' => $ip,
            ]);
        }

        return redirect()->route('polls.show', $poll)->with('success', 'Vote submitted!');
    }
}
