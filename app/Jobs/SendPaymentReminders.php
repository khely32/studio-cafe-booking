<?php

namespace App\Jobs;

use App\Mail\PaymentReminder;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPaymentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        if ((int) Setting::get('payment_reminder_enabled', '1') !== 1) {
            return;
        }

        $days = max(1, (int) Setting::get('payment_reminder_days', '3'));
        $today = Carbon::today();

        $bookings = Booking::whereIn('payment_status', ['pending', 'partial'])
            ->whereNull('payment_reminder_sent_at')
            ->where('booking_date', '>=', $today)
            ->where('booking_date', '<=', $today->copy()->addDays($days))
            ->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')
            ->get();

        foreach ($bookings as $booking) {
            try {
                Mail::to($booking->customer_email)->send(new PaymentReminder($booking));
                $booking->update(['payment_reminder_sent_at' => now()]);
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
