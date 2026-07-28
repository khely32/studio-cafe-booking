<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap');
        body { font-family: 'DM Sans', Arial, sans-serif; background: #FAF6F1; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #fff; }
        .header {
            padding: 48px 40px; text-align: center;
            background: linear-gradient(135deg, #1A1A1A, #3a3028);
            position: relative;
        }
        .logo {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #C9A96E, #A8884D);
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif; font-size: 20px;
            font-weight: 700; color: #fff; margin-bottom: 16px;
        }
        .header h1 {
            font-family: 'Playfair Display', serif; font-size: 28px;
            color: #fff; margin: 0; font-weight: 700;
        }
        .header p { color: rgba(255,255,255,0.6); margin-top: 8px; font-size: 14px; }
        .content { padding: 40px; }
        .ref-box {
            text-align: center; padding: 24px;
            background: #FAF6F1; border-radius: 16px;
            margin-bottom: 32px; border: 1px solid #F0E8DD;
        }
        .ref-box h2 { font-family: 'Playfair Display', serif; font-size: 16px; color: #888; margin: 0; font-weight: 400; }
        .ref-box .ref { font-family: monospace; font-size: 24px; font-weight: 700; color: #2C2C2C; margin-top: 8px; letter-spacing: 3px; }
        .detail-block { margin-bottom: 28px; }
        .detail-block h3 {
            font-family: 'Playfair Display', serif; font-size: 16px;
            color: #2C2C2C; margin: 0 0 14px; padding-bottom: 10px;
            border-bottom: 1px solid #F0E8DD;
        }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f8f8f8; }
        .row:last-child { border-bottom: none; }
        .row .label { color: #888; font-size: 13px; }
        .row .value { font-weight: 600; font-size: 13px; color: #2C2C2C; }
        .payment-box {
            padding: 24px; border-radius: 12px;
            background: linear-gradient(135deg, #FFFDF9, #FAF6F1);
            border: 1px solid #F0E8DD; margin: 24px 0;
        }
        .payment-box h4 { font-family: 'Playfair Display', serif; font-size: 15px; color: #A8884D; margin: 0 0 10px; }
        .payment-box p { font-size: 13px; color: #666; line-height: 1.8; margin: 0; }
        .reminder-box {
            padding: 20px; border-radius: 12px;
            background: #FFF8E1; border: 1px solid #FFECB3;
            margin: 20px 0;
        }
        .reminder-box h4 { font-family: 'Playfair Display', serif; font-size: 14px; color: #F57F17; margin: 0 0 8px; }
        .reminder-box p { font-size: 12px; color: #8D6E04; line-height: 1.8; margin: 0; }
        .footer {
            text-align: center; padding: 32px 40px;
            background: #1A1A1A; color: rgba(255,255,255,0.4);
            font-size: 12px;
        }
        .footer .brand { font-family: 'Playfair Display', serif; font-size: 18px; color: #fff; margin-bottom: 4px; }
        .footer .brand span { color: #C9A96E; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">56</div>
            <h1>Booking Confirmed</h1>
            <p>56'30 Studio Cafe</p>
        </div>

        <div class="content">
            <p style="font-size:14px;color:#666;line-height:1.8;">Hi {{ $booking->customer_name }}, your booking has been confirmed! We're excited to capture your moments.</p>

            <div class="ref-box">
                <h2>Your Booking Reference</h2>
                <div class="ref">{{ $booking->booking_ref }}</div>
            </div>

            <div class="detail-block">
                <h3>Booking Details</h3>
                <div class="row"><span class="label">Package</span><span class="value">{{ $booking->service->name }}</span></div>
                <div class="row"><span class="label">Date</span><span class="value">{{ $booking->booking_date->format('l, F j, Y') }}</span></div>
                <div class="row"><span class="label">Time</span><span class="value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span></div>
                <div class="row"><span class="label">Duration</span><span class="value">{{ $booking->service->duration_label }}</span></div>
                <div class="row"><span class="label">Pax</span><span class="value">{{ $booking->num_pax }}</span></div>
            </div>

            <div class="detail-block">
                <h3>Payment Summary</h3>
                <div class="row"><span class="label">Total Amount</span><span class="value" style="font-size:16px;color:#A8884D;">₱{{ number_format($booking->total_amount, 2) }}</span></div>
                <div class="row"><span class="label">Amount Paid</span><span class="value">₱{{ number_format($booking->amount_paid, 2) }}</span></div>
                @if($booking->payment_status === 'partial')
                <div class="row"><span class="label">Remaining</span><span class="value" style="color:#F57F17;">₱{{ number_format($booking->total_amount - $booking->amount_paid, 2) }}</span></div>
                @endif
            </div>

            <div class="payment-box">
                <h4>Payment Instructions</h4>
                <p>Send your payment and proof of payment to our FB page messenger (Required).<br><br>
                <strong>GCash / PayMaya</strong><br>
                Ma. Jaliha Unlayao — 09533651548</p>
            </div>

            <div class="reminder-box">
                <h4>Important Reminders</h4>
                <p>• Please be on time. 10-minute grace period applies.<br>
                • Confirmed slots are non-refundable but can be re-scheduled 1-2 days before.<br>
                • No show = cancelled slot (down payment forfeited).<br>
                • Self-capture studio — just you, the camera, and remote.</p>
            </div>
        </div>

        <div class="footer">
            <div class="brand">56'30 <span>Studio</span></div>
            <p>&copy; {{ date('Y') }} 56'30 Studio Cafe. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
