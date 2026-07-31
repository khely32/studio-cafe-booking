@extends('admin.layout')
@section('title', 'Poll Results')

@section('content')
<style>
    .poll-bar { height: 10px; background: rgba(0,0,0,0.04); border-radius: 100px; overflow: hidden; }
    .poll-bar-fill { height: 100%; border-radius: 100px; background: var(--gradient-1); transition: width 0.8s cubic-bezier(0.4,0,0.2,1); }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Poll Results</div>
        <div class="page-subtitle">{{ $poll->question }}</div>
    </div>
    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">← Back to Polls</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div style="background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);overflow:hidden;box-shadow:0 10px 40px rgba(139,111,71,0.08);">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:16px;font-weight:700;font-family:Poppins,sans-serif;color:var(--gray-900);">{{ $poll->question }}</h2>
            <span style="font-size:13px;color:var(--gray-500);">{{ $poll->total_votes }} total votes</span>
        </div>
        <div style="padding:24px;">
            @foreach($poll->options as $opt)
            @php $pct = $poll->total_votes > 0 ? round(($opt->vote_count / $poll->total_votes) * 100, 1) : 0; @endphp
            <div style="margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-weight:600;font-size:14px;color:var(--gray-800);">{{ $opt->text }}</span>
                    <span style="font-size:13px;color:var(--gray-500);font-weight:500;">{{ $opt->vote_count }} vote{{ $opt->vote_count !== 1 ? 's' : '' }} ({{ $pct }}%)</span>
                </div>
                <div class="poll-bar">
                    <div class="poll-bar-fill" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div style="background:linear-gradient(135deg,rgba(255,255,255,0.9),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);overflow:hidden;box-shadow:0 10px 40px rgba(139,111,71,0.08);align-self:start;">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(0,0,0,0.04);">
            <h2 style="font-size:14px;font-weight:700;font-family:Poppins,sans-serif;color:var(--gray-900);">Info</h2>
        </div>
        <div style="padding:24px;">
            <div style="margin-bottom:16px;">
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:4px;">Created</div>
                <div style="font-weight:500;font-size:14px;color:var(--gray-800);">{{ $poll->created_at->format('M d, Y') }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:4px;">Status</div>
                <span class="badge {{ $poll->is_closed ? 'badge-neutral' : ($poll->is_active ? 'badge-success' : 'badge-neutral') }}">{{ $poll->is_closed ? 'Closed' : 'Open' }}</span>
            </div>
            <div style="margin-bottom:24px;">
                <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-400);margin-bottom:4px;">Mode</div>
                <div style="font-weight:500;font-size:14px;color:var(--gray-800);">{{ $poll->allow_multiple ? 'Multiple selections' : 'Single selection' }}</div>
            </div>
            @if(!$poll->is_closed)
            <form method="POST" action="{{ route('admin.polls.toggle-close', $poll) }}">
                @csrf
                <button type="submit" class="btn btn-danger" style="width:100%;">Close Poll</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
