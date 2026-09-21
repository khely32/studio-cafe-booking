<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed! – 56'30 Studio Cafe</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap');
        body { font-family: 'DM Sans', Arial, sans-serif; background: #FAF6F1; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header {
            padding: 44px 40px; text-align: center;
            background: linear-gradient(135deg, #1A1A1A 0%, #3A3028 100%);
            position: relative;
        }
        .logo {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #C9A96E, #A8884D);
            display: inline-flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif; font-size: 20px;
            font-weight: 700; color: #ffffff; margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(201, 169, 110, 0.35);
        }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: #ffffff; margin: 0; font-weight: 700; letter-spacing: 0.5px; }
        .header p { color: rgba(255, 255, 255, 0.65); margin: 8px 0 0; font-size: 14px; }
        .header .divider { width: 40px; height: 2px; background: #C9A96E; margin: 18px auto 0; border-radius: 2px; }
        .content { padding: 36px 40px; }
        .greeting { font-size: 15px; color: #555; line-height: 1.7; margin: 0 0 24px; }
        .greeting strong { color: #2C2C2C; }
        .ref-box {
            text-align: center; padding: 22px 24px;
            background: linear-gradient(135deg, #FFFDF9, #FAF6F1);
            border: 1px solid #F0E8DD; border-radius: 14px;
            margin-bottom: 28px;
        }
        .ref-box h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #A8884D; margin: 0 0 8px; font-weight: 600; }
        .ref-box .ref { font-family: 'Courier New', monospace; font-size: 26px; font-weight: 700; color: #2C2C2C; letter-spacing: 4px; }
        .summary {
            border: 1px solid #F0E8DD; border-radius: 14px;
            overflow: hidden; margin-bottom: 24px;
        }
        .summary .summary-title {
            background: #FAF6F1; padding: 14px 22px;
            font-family: 'Playfair Display', serif; font-size: 16px;
            color: #2C2C2C; font-weight: 600; border-bottom: 1px solid #F0E8DD;
        }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 12px 22px; font-size: 13px; border-bottom: 1px solid #F8F3EC; vertical-align: top; }
        .summary tr:last-child td { border-bottom: none; }
        .summary .label { color: #999; width: 42%; }
        .summary .value { font-weight: 600; color: #2C2C2C; }
        .payment-box {
            padding: 20px 22px; border-radius: 12px;
            background: linear-gradient(135deg, #FFFDF9, #FAF6F1);
            border: 1px solid #F0E8DD; margin: 24px 0;
        }
        .payment-box h4 { font-family: 'Playfair Display', serif; font-size: 15px; color: #A8884D; margin: 0 0 10px; }
        .payment-box p { font-size: 13px; color: #666; line-height: 1.8; margin: 0; }
        .info-box {
            padding: 20px 22px; border-radius: 12px;
            background: #FFF8E1; border: 1px solid #FFECB3; margin: 20px 0;
        }
        .info-box h4 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #F57F17; margin: 0 0 10px; font-weight: 700; }
        .info-box p { font-size: 13px; color: #8D6E04; line-height: 1.8; margin: 0; }
        .btn {
            display: inline-block; padding: 14px 34px; border-radius: 30px;
            background: linear-gradient(135deg, #C9A96E, #A8884D);
            color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600;
            margin: 8px 0 4px; letter-spacing: 0.5px;
        }
        .footer {
            text-align: center; padding: 30px 40px;
            background: #1A1A1A; color: rgba(255, 255, 255, 0.45);
            font-size: 12px; line-height: 1.8;
        }
        .footer .brand { font-family: 'Playfair Display', serif; font-size: 18px; color: #ffffff; margin-bottom: 6px; }
        .footer .brand span { color: #C9A96E; }
        @media only screen and (max-width: 480px) {
            .content, .header, .footer { padding-left: 20px; padding-right: 20px; }
            .summary td { padding: 10px 16px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Header: Cafe branding -->
        <div class="header">
            <div class="logo">56</div>
            <h1>Booking Confirmed!</h1>
            <p>56'30 Studio Cafe</p>
            <div class="divider"></div>
        </div>

        <div class="content">

            <!-- Confirmation greeting -->
            <p class="greeting">Hi <strong>{{ $booking->customer_name }}</strong>, your booking has been confirmed! We're excited to capture every moment at the studio.</p>

            <!-- Booking reference -->
            <div class="ref-box">
                <h2>Your Booking Reference</h2>
                <div class="ref">{{ $booking->booking_ref }}</div>
            </div>

            <!-- Structured booking summary -->
            <div class="summary">
                <div class="summary-title">Booking Details</div>
                <table role="presentation">
                    <tr>
                        <td class="label">Customer Name</td>
                        <td class="value">{{ $booking->customer_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Booking ID</td>
                        <td class="value">{{ $booking->booking_ref }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date</td>
                        <td class="value">{{ $booking->booking_date->format('l, F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Time</td>
                        <td class="value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Number of Guests</td>
                        <td class="value">{{ $booking->num_pax }}</td>
                    </tr>
                    <tr>
                        <td class="label">Package</td>
                        <td class="value">{{ $booking->service->name }} ({{ $booking->service->duration_label }})</td>
                    </tr>
                    <tr>
                        <td class="label">Contact</td>
                        <td class="value">{{ $booking->customer_phone }}</td>
                    </tr>
                    <tr>
                        <td class="label">Special Requests</td>
                        <td class="value">{{ $booking->special_requests ?? '—' }}</td>
                    </tr>
                </table>
            </div>

            <div class="payment-box">
                <h4>Payment Summary</h4>
                <p>
                    Total Amount: <strong>₱{{ number_format($booking->total_amount, 2) }}</strong><br>
                    Amount Paid: <strong>₱{{ number_format($booking->amount_paid, 2) }}</strong>
                    @if($booking->payment_status === 'partial')
                        <br>Remaining: <strong style="color:#F57F17;">₱{{ number_format($booking->total_amount - $booking->amount_paid, 2) }}</strong>
                    @endif
                </p>
                <p style="margin-top:12px;padding-top:12px;border-top:1px solid #F0E8DD;">
                    Send your payment and proof of payment to our FB page messenger (Required).<br>
                    <strong>GCash / PayMaya</strong><br>
                    Ma. Jaliha Unlayao — 09533651548
                </p>
            </div>

            <div class="info-box">
                <h4>Changes &amp; Cancellation</h4>
                <p>
                    Need to reschedule or have questions? Message us on our <strong>Facebook page</strong> or call
                    <strong>09533651548</strong>. Please be on time — a 10-minute grace period applies.<br><br>
                    • Confirmed slots are non-refundable but can be re-scheduled 1–2 days before.<br>
                    • No show = cancelled slot (down payment forfeited).<br>
                    • Self-capture studio — just you, the camera, and remote.
                </p>
            </div>

            <div style="text-align:center; margin-top:28px;">
                <a href="{{ url('/booking') }}" class="btn">Book Another Session</a>
            </div>
        </div>

        <!-- Footer notice -->
        <div class="footer">
            <div class="brand">56'30 <span>Studio</span></div>
            <p>&copy; {{ date('Y') }} 56'30 Studio Cafe. All rights reserved.<br>
            5630 Studio Cafe · Self-Capture Studio · Visit us on Facebook</p>
        </div>
    </div>
</body>
</html>