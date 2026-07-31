@extends('layouts.app')

@section('styles')
<style>
    .confirm-hero {
        padding: 120px 40px 60px; text-align: center;
        background: linear-gradient(135deg, #3E2723, #6F4E37, #3E2723);
        position: relative; overflow: hidden;
    }
    .confirm-hero::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 50% 80%, rgba(201,169,110,0.12), transparent 50%),
            radial-gradient(ellipse at 30% 20%, rgba(255,255,255,0.04), transparent 40%);
    }
    .confirm-hero .grain {
        position: absolute; inset: 0; opacity: 0.04;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='6' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        background-size: 512px 512px; pointer-events: none;
    }
    .confirm-check {
        width: 80px; height: 80px; border-radius: 50%;
        background: linear-gradient(135deg, #D4AF37, #B08968);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 36px; color: #fff; margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(201,169,110,0.3);
        animation: fadeInUp 0.6s ease; position: relative; z-index: 1;
    }
    .confirm-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 36px; color: #fff; font-weight: 700;
        position: relative; z-index: 1; animation: fadeInUp 0.6s ease 0.1s both;
    }
    .confirm-hero .ref-badge {
        display: inline-block; margin-top: 16px; padding: 8px 24px;
        background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.3);
        border-radius: 100px; font-family: monospace; font-size: 16px;
        color: #C9A96E; letter-spacing: 2px; position: relative; z-index: 1;
        animation: fadeInUp 0.6s ease 0.2s both;
    }
    .detail-card {
        background: rgba(255,255,255,0.88); backdrop-filter: blur(20px);
        border-radius: 18px; padding: 32px; margin-bottom: 24px;
        border: 1px solid rgba(201,169,110,0.12);
        box-shadow: 0 8px 32px rgba(44,30,20,0.07);
    }
    .detail-card h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 18px; font-weight: 600; margin-bottom: 20px;
        padding-bottom: 12px; border-bottom: 1px solid rgba(240,232,221,0.6);
        color: var(--gray-900);
    }
    .detail-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row .label { font-size: 13px; color: var(--gray-500); }
    .detail-row .value { font-weight: 600; font-size: 14px; color: var(--gray-800); }
    .payment-instructions {
        padding: 24px; border-radius: 16px;
        background: linear-gradient(135deg, rgba(255,253,249,0.9), rgba(247,242,235,0.9));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(201,169,110,0.15);
        margin-top: 24px;
    }
    .payment-instructions h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 16px; margin-bottom: 12px; color: #8B6F47;
    }
    .payment-instructions p { font-size: 13px; color: var(--gray-600); line-height: 1.8; }
    .remaining-badge {
        margin-top: 12px; padding: 12px 16px;
        background: rgba(255,248,225,0.9); border: 1px solid rgba(255,236,179,0.5);
        border-radius: var(--radius-sm); font-size: 13px; color: #92400E;
    }
</style>
@endsection

@section('content')
<div class="confirm-hero">
    <div class="grain"></div>
    <div class="confirm-hero" style="padding:0;">
        <div class="confirm-check">✓</div>
        <h1>Booking Confirmed!</h1>
        <div class="ref-badge">#{{ $booking->booking_ref }}</div>
    </div>
</div>

<div class="container">
    <div style="max-width:640px;margin:0 auto;">

        <div class="detail-card">
            <h3>Booking Details</h3>
            <div class="detail-row"><span class="label">Package</span><span class="value">{{ $booking->service->name }}</span></div>
            <div class="detail-row"><span class="label">Date</span><span class="value">{{ $booking->booking_date->format('l, F j, Y') }}</span></div>
            <div class="detail-row"><span class="label">Time</span><span class="value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span></div>
            <div class="detail-row"><span class="label">Duration</span><span class="value">{{ $booking->service->duration_label }}</span></div>
            <div class="detail-row"><span class="label">Pax</span><span class="value">{{ $booking->num_pax }}</span></div>
        </div>

        <div class="detail-card">
            <h3>Customer Information</h3>
            <div class="detail-row"><span class="label">Name</span><span class="value">{{ $booking->customer_name }}</span></div>
            <div class="detail-row"><span class="label">Email</span><span class="value">{{ $booking->customer_email }}</span></div>
            <div class="detail-row"><span class="label">Phone</span><span class="value">{{ $booking->customer_phone }}</span></div>
        </div>

        <div class="detail-card">
            <h3>Payment Summary</h3>
            <div class="detail-row"><span class="label">Total Amount</span><span class="value" style="font-family:'Poppins',sans-serif;font-size:20px;color:#8B6F47;">₱{{ number_format($booking->total_amount, 2) }}</span></div>
            <div class="detail-row"><span class="label">Amount Paid</span><span class="value">₱{{ number_format($booking->amount_paid, 2) }}</span></div>
            <div class="detail-row"><span class="label">Method</span><span class="value" style="text-transform:uppercase;">{{ $booking->payment_method }}</span></div>
            @if($booking->payment_status === 'partial')
            <div class="remaining-badge">Remaining balance of <strong>₱{{ number_format($booking->total_amount - $booking->amount_paid, 2) }}</strong> to be settled after the session.</div>
            @endif
        </div>

        <div class="payment-instructions">
            <h4>Payment Instructions</h4>
            <p>Send your payment and proof of payment to our FB page messenger (Required).<br><br>
            <strong>GCash / PayMaya</strong><br>
            Ma. Jaliha Unlayao — 09533651548</p>
        </div>

        <div style="text-align:center;margin-top:32px;">
            <a href="{{ route('home') }}" class="btn btn-primary" style="padding:14px 40px;">Back to Home</a>
        </div>
    </div>
</div>
@endsection