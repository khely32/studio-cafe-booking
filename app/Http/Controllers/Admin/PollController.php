<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::withCount('votes')->withCount('options')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.polls.index', compact('polls'));
    }

    public function create()
    {
        return view('admin.polls.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:255',
        ]);

        $poll = Poll::create([
            'question' => $validated['question'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        foreach ($validated['options'] as $index => $option) {
            PollOption::create([
                'poll_id' => $poll->id,
                'option_text' => $option,
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.polls.index')->with('success', 'Poll created.');
    }

    public function show(Poll $poll)
    {
        $poll->load('options');
        $results = $poll->getResults();
        $totalVotes = $poll->total_votes;
        return view('admin.polls.show', compact('poll', 'results', 'totalVotes'));
    }

    public function edit(Poll $poll)
    {
        $poll->load('options');
        return view('admin.polls.edit', compact('poll'));
    }

    public function update(Request $request, Poll $poll)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'is_closed' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_closed'] = $request->boolean('is_closed');

        $poll->update($validated);

        return redirect()->route('admin.polls.index')->with('success', 'Poll updated.');
    }

    public function destroy(Poll $poll)
    {
        $poll->delete();
        return redirect()->route('admin.polls.index')->with('success', 'Poll deleted.');
    }

    public function toggleClose(Poll $poll)
    {
        $poll->update(['is_closed' => !$poll->is_closed]);
        $status = $poll->is_closed ? 'closed' : 'opened';
        return redirect()->back()->with('success', "Poll {$status} for voting.");
    }
}
