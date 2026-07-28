@extends('admin.layout')
@section('title', 'Bookings')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Bookings</div>
        <div class="page-subtitle">Manage all studio bookings</div>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-body" style="padding:16px 24px;">
        <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <div class="search-box" style="flex:1;min-width:200px;">
                <span class="icon">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, ref..." class="form-control" style="padding-left:36px;">
            </div>
            <select name="status" class="form-control" style="width:160px;">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','completed','cancelled','no_show'] as $s)
                <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="form-control" style="width:160px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">Clear</a>
        </form>
    </div>
</div>

<div class="card">
    @if($bookings->isEmpty())
    <div class="empty-state"><div class="icon">📅</div><p>No bookings found.</p></div>
    @else
    <div style="overflow-x:auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Date & Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--primary);">{{ $b->booking_ref }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $b->customer_name }}</div>
                        <div style="font-size:12px;color:var(--gray-500);">{{ $b->customer_email }}</div>
                    </td>
                    <td>{{ $b->service->name }}</td>
                    <td>
                        <div>{{ $b->booking_date->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:var(--gray-500);">{{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;">₱{{ number_format($b->total_amount, 2) }}</div>
                        @if($b->payment_status==='partial')<div style="font-size:11px;color:var(--orange);">Partial</div>@endif
                    </td>
                    <td><span class="page-card-badge badge-{{ $b->status_badge }}">{{ ucfirst($b->status) }}</span></td>
                    <td><a href="{{ route('admin.booking.detail', $b) }}" class="btn btn-sm btn-ghost">View →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:16px 24px;border-top:1px solid var(--gray-100);">{{ $bookings->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
