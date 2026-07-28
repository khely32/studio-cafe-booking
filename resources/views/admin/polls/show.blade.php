@extends('admin.layout')
@section('title', 'Poll Results')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Poll Results</div>
        <div class="page-subtitle">{{ $poll->question }}</div>
    </div>
    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">← Back to Polls</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div class="card">
        <div class="card-header">
            <h2>{{ $poll->question }}</h2>
            <span style="font-size:13px;color:var(--gray-500);">{{ $poll->total_votes }} total votes</span>
        </div>
        <div class="card-body">
            @foreach($poll->options as $opt)
            @php $pct = $poll->total_votes > 0 ? round(($opt->vote_count / $poll->total_votes) * 100, 1) : 0; @endphp
            <div style="margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-weight:500;font-size:14px;">{{ $opt->text }}</span>
                    <span style="font-size:13px;color:var(--gray-500);">{{ $opt->vote_count }} votes ({{ $pct }}%)</span>
                </div>
                <div style="height:8px;background:var(--gray-100);border-radius:100px;overflow:hidden;">
                    <div style="height:100%;width:{{ $pct }}%;background:var(--primary);border-radius:100px;transition:width 0.5s;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h2>Info</h2></div>
        <div class="card-body">
            <div style="margin-bottom:12px;">
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Created</div>
                <div style="font-weight:500;">{{ $poll->created_at->format('M d, Y') }}</div>
            </div>
            <div style="margin-bottom:12px;">
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Status</div>
                <span class="page-card-badge {{ $poll->is_closed ? 'badge-cancelled' : ($poll->is_active ? 'badge-online' : 'badge-offline') }}">{{ $poll->is_closed ? 'Closed' : 'Open' }}</span>
            </div>
            <div style="margin-bottom:20px;">
                <div style="font-size:12px;color:var(--gray-500);margin-bottom:2px;">Mode</div>
                <div style="font-weight:500;font-size:13px;">{{ $poll->allow_multiple ? 'Multiple selections' : 'Single selection' }}</div>
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
