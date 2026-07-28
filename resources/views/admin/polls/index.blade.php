@extends('admin.layout')
@section('title', 'Polls')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Polls</div>
        <div class="page-subtitle">Create and manage audience polls</div>
    </div>
    <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">+ New Poll</a>
</div>

<div class="card">
    @if($polls->isEmpty())
    <div class="empty-state"><div class="icon">🗳️</div><p>No polls yet.</p></div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Options</th>
                <th>Votes</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($polls as $poll)
            <tr>
                <td style="font-weight:500;max-width:300px;">{{ $poll->question }}</td>
                <td>{{ $poll->options->count() }}</td>
                <td style="font-weight:600;">{{ $poll->total_votes }}</td>
                <td><span class="page-card-badge {{ $poll->is_active ? ($poll->is_closed ? 'badge-pending' : 'badge-online') : 'badge-offline' }}">{{ $poll->is_closed ? 'Closed' : ($poll->is_active ? 'Active' : 'Inactive') }}</span></td>
                <td style="display:flex;gap:6px;">
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
