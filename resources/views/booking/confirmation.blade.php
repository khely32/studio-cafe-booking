@extends('layouts.app')

@section('styles')
<style>
    .confirm-hero {
        padding: 120px 40px 60px; text-align: center;
        background: linear-gradient(135deg, var(--espresso), #3a3028);
        position: relative;
    }
    .confirm-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 50% 80%, rgba(201,169,110,0.1), transparent 50%);
    }
    .confirm-check {
        width: 80px; height: 80px; border-radius: 50%;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 36px; color: #fff; margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(201,169,110,0.3);
        animation: fadeInUp 0.6s ease;
    }
    .confirm-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 36px; color: #fff; font-weight: 700;
        position: relative; animation: fadeInUp 0.6s ease 0.1s both;
    }
    .confirm-hero .ref-badge {
        display: inline-block; margin-top: 16px; padding: 8px 24px;
        background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.3);
        border-radius: 100px; font-family: monospace; font-size: 16px;
        color: var(--gold-light); letter-spacing: 2px;
        animation: fadeInUp 0.6s ease 0.2s both;
    }
    .detail-card {
        background: #fff; border-radius: var(--radius-lg);
        padding: 32px; margin-bottom: 24px;
        border: 1px solid rgba(201,169,110,0.1);
        box-shadow: var(--shadow-sm);
    }
    .detail-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 18px; font-weight: 600; margin-bottom: 20px;
        padding-bottom: 12px; border-bottom: 1px solid var(--cream-dark);
    }
    .detail-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row .label { font-size: 13px; color: var(--charcoal-light); }
    .detail-row .value { font-weight: 600; font-size: 14px; }
    .payment-instructions {
        padding: 24px; border-radius: var(--radius-md);
        background: linear-gradient(135deg, #FFFDF9, var(--cream));
        border: 1px solid rgba(201,169,110,0.15);
        margin-top: 24px;
    }
    .payment-instructions h4 {
        font-family: 'Playfair Display', serif;
        font-size: 16px; margin-bottom: 12px; color: var(--gold-dark);
    }
    .payment-instructions p { font-size: 13px; color: var(--charcoal-light); line-height: 1.8; }
    .remaining-badge {
        margin-top: 12px; padding: 12px 16px;
        background: #FFF8E1; border: 1px solid #FFECB3;
        border-radius: var(--radius-sm); font-size: 13px; color: #F57F17;
    }
</style>
@endsection

@section('content')
<div class="confirm-hero">
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
            <div class="detail-row"><span class="label">Total Amount</span><span class="value" style="font-family:'Playfair Display',serif;font-size:20px;color:var(--gold-dark);">₱{{ number_format($booking->total_amount, 2) }}</span></div>
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
