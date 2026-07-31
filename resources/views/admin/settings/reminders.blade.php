@extends('admin.layout')
@section('title', 'Payment Reminders')

@section('content')
<style>
    .rm-grid { display: grid; grid-template-columns: 420px 1fr; gap: 20px; align-items: start; }
    .rm-panel {
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.6));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.5);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: 0 10px 40px rgba(139,111,71,0.08);
    }
    .rm-panel h2 { font-size: 16px; font-weight: 700; color: var(--gray-900); margin-bottom: 4px; }
    .rm-panel .sub { font-size: 12px; color: var(--gray-500); margin-bottom: 16px; }
    .rm-field { margin-bottom: 18px; }
    .rm-field label { display: block; font-size: 13px; font-weight: 600; color: var(--gray-700); margin-bottom: 6px; }
    .rm-switch { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius-sm); }
    .rm-switch-text { font-size: 13px; font-weight: 600; color: var(--gray-800); }
    .rm-switch-sub { font-size: 12px; color: var(--gray-500); margin-top: 2px; }
    .rm-toggle { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
    .rm-toggle input { opacity: 0; width: 0; height: 0; }
    .rm-slider { position: absolute; inset: 0; background: #D1D5DB; border-radius: 9999px; transition: background 0.2s; cursor: pointer; }
    .rm-slider::before { content: ''; position: absolute; width: 18px; height: 18px; border-radius: 50%; background: #fff; top: 3px; left: 3px; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .rm-toggle input:checked + .rm-slider { background: #1A3B32; }
    .rm-toggle input:checked + .rm-slider::before { transform: translateX(20px); }
    .rm-note {
        margin-top: 14px; padding: 12px 14px; border-radius: var(--radius-sm);
        background: #FFF8E1; border: 1px solid #FFECB3; font-size: 12px; color: #8D6E04; line-height: 1.6;
    }
    .rm-table-wrap { background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; box-shadow: 0 10px 40px rgba(139,111,71,0.08); }
    .rm-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .rm-table th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; color: var(--gray-500); border-bottom: 1px solid var(--gray-200); background: #FAFAF9; }
    .rm-table td { padding: 12px 16px; border-bottom: 1px solid #F3F4F6; color: var(--gray-800); vertical-align: middle; }
    .rm-table tr:last-child td { border-bottom: none; }
    .rm-table tr:hover td { background: #FAFAF9; }
    .rm-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; }
    .rm-badge.sent { background: #E7F1EC; color: #1A3B32; }
    .rm-badge.pending { background: #FEF3C7; color: #B45309; }
    .rm-badge.due-soon { background: #FEE2E2; color: #B91C1C; }
    .rm-empty { padding: 40px 16px; text-align: center; color: var(--gray-500); font-size: 13px; }
    @media (max-width: 1000px) { .rm-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Payment Reminders</div>
        <div class="page-subtitle">Automatic email reminders for unpaid bookings before their due date.</div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:16px;">{{ session('success') }}</div>
@endif
@if(session('warning'))
    <div class="alert alert-warning" style="margin-bottom:16px;">{{ session('warning') }}</div>
@endif

<div class="rm-grid">
    <div class="rm-panel">
        <h2>Reminder settings</h2>
        <div class="sub">Applied on the next scheduled run (daily).</div>

        <form method="POST" action="{{ route('admin.settings.reminders.update') }}">
            @csrf
            <div class="rm-field">
                <label>Automatically send payment reminders</label>
                <div class="rm-switch">
                    <div>
                        <div class="rm-switch-text">Reminders enabled</div>
                        <div class="rm-switch-sub">Email unpaid bookings before their due date</div>
                    </div>
                    <label class="rm-toggle">
                        <input type="hidden" name="payment_reminder_enabled" value="0">
                        <input type="checkbox" name="payment_reminder_enabled" value="1" {{ $enabled ? 'checked' : '' }}>
                        <span class="rm-slider"></span>
                    </label>
                </div>
            </div>

            <div class="rm-field">
                <label for="days">Remind how many days before the due date?</label>
                <input type="number" name="payment_reminder_days" id="days" class="form-control" min="1" max="30" value="{{ $days }}">
            </div>

            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>

        <div class="rm-note">
            <strong>Due date</strong> = the booking date. Reminders are sent once per booking — bookings are marked as reminded so they aren't emailed again. Only bookings with a remaining balance (<em>pending</em> or <em>partial</em> payment) are reminded.
        </div>

        <form method="POST" action="{{ route('admin.settings.reminders.run') }}" style="margin-top:16px;">
            @csrf
            <button type="submit" class="btn btn-secondary">Run reminders now</button>
        </form>
    </div>

    <div class="rm-table-wrap">
        <table class="rm-table">
            <thead>
                <tr>
                    <th>Booking date</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Payment</th>
                    <th>Reminder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php
                    $dueIn = \Carbon\Carbon::parse($b->booking_date)->diffInDays(\Carbon\Carbon::today(), false);
                    $dueSoon = !$b->payment_reminder_sent_at && $dueIn >= 0 && $dueIn <= $days;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($b->booking_date)->format('D, M j, Y') }}<br><span style="color:var(--gray-500);font-size:12px;">{{ \Carbon\Carbon::parse($b->booking_time)->format('g:i A') }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $b->customer_name }}</div>
                        <div style="color:var(--gray-500);font-size:12px;">{{ $b->booking_ref }}</div>
                    </td>
                    <td style="color:var(--gray-600);">{{ $b->customer_email }}</td>
                    <td>
                        <div>{{ $b->payment_status === 'partial' ? 'Partial' : 'Unpaid' }}</div>
                        <div style="color:var(--gray-500);font-size:12px;">₱{{ number_format(($b->total_amount ?? 0) - ($b->amount_paid ?? 0), 2) }} due of ₱{{ number_format($b->total_amount ?? 0, 2) }}</div>
                    </td>
                    <td>
                        @if($b->payment_reminder_sent_at)
                            <span class="rm-badge sent">Sent {{ \Carbon\Carbon::parse($b->payment_reminder_sent_at)->format('M j') }}</span>
                        @elseif($dueSoon)
                            <span class="rm-badge due-soon">Due soon — will remind</span>
                        @else
                            <span class="rm-badge pending">Not yet in window</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="rm-empty">No unpaid bookings.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
