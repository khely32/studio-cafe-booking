@extends('admin.layout')
@section('title', 'Templates')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Templates</div>
        <div class="page-subtitle">Manage notification and email templates</div>
    </div>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">+ New Template</a>
</div>

<div class="card">
    @if($templates->isEmpty())
    <div class="empty-state"><div class="icon">📋</div><p>No templates yet.</p></div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $t)
            <tr>
                <td style="font-weight:500;">{{ $t->title }}</td>
                <td><span class="page-card-badge badge-offline">{{ ucfirst($t->type) }}</span></td>
                <td style="color:var(--gray-500);">{{ Str::limit($t->subject, 50) }}</td>
                <td><span class="page-card-badge {{ $t->is_active ? 'badge-online' : 'badge-offline' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td style="display:flex;gap:6px;">
                    <a href="{{ route('admin.templates.edit', $t) }}" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.templates.destroy', $t) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
