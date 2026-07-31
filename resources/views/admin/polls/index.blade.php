@extends('admin.layout')
@section('title', 'Polls')

@section('content')
<style>
    .glass-table { width:100%; border-collapse:separate; border-spacing:0; }
    .glass-table thead th {
        padding:14px 18px; text-align:left; font-size:12px; font-weight:600;
        text-transform:uppercase; letter-spacing:0.5px; color:rgba(255,255,255,0.9);
        background:var(--gradient-1); font-family:Poppins,sans-serif;
    }
    .glass-table thead th:first-child { border-radius:14px 0 0 0; }
    .glass-table thead th:last-child { border-radius:0 14px 0 0; text-align:right; }
    .glass-table tbody tr { transition:all 0.2s; }
    .glass-table tbody td { padding:14px 18px; font-size:14px; color:var(--gray-600); border-bottom:1px solid rgba(0,0,0,0.04); }
    .glass-table tbody tr:last-child td:first-child { border-radius:0 0 0 14px; }
    .glass-table tbody tr:last-child td:last-child { border-radius:0 0 14px 0; }
    .glass-table tbody tr:last-child td { border-bottom:none; }
    .glass-table tbody tr:hover { background:rgba(201,169,110,0.04); }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Polls</div>
        <div class="page-subtitle">Create and manage audience polls</div>
    </div>
    <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">+ New Poll</a>
</div>

<div style="background:linear-gradient(135deg,rgba(255,255,255,0.85),rgba(255,255,255,0.6));backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:var(--radius);overflow:hidden;box-shadow:0 10px 35px rgba(139,111,71,0.06);">
    @if($polls->isEmpty())
    <div style="padding:60px 20px;text-align:center;"><div style="width:64px;height:64px;border-radius:16px;background:rgba(139,111,71,0.08);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 12px;">🗳️</div><p style="color:var(--gray-400);font-size:14px;">No polls yet.</p></div>
    @else
    <table class="glass-table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Options</th>
                <th>Votes</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($polls as $poll)
            <tr>
                <td style="font-weight:500;max-width:300px;color:var(--gray-900);">{{ $poll->question }}</td>
                <td>{{ $poll->options->count() }}</td>
                <td style="font-weight:600;color:var(--gray-900);">{{ $poll->total_votes }}</td>
                <td><span class="badge {{ $poll->is_closed ? 'badge-neutral' : ($poll->is_active ? 'badge-success' : 'badge-neutral') }}">{{ $poll->is_closed ? 'Closed' : ($poll->is_active ? 'Active' : 'Inactive') }}</span></td>
                <td style="display:flex;gap:6px;justify-content:flex-end;">
                    <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-sm btn-secondary">Results</a>
                    <a href="{{ route('admin.polls.edit', $poll) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.polls.toggle-close', $poll) }}" style="display:inline;">@csrf
                        <button type="submit" class="btn btn-sm {{ $poll->is_closed ? 'btn-secondary' : 'btn-danger' }}">{{ $poll->is_closed ? 'Reopen' : 'Close' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.polls.destroy', $poll) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Del</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
