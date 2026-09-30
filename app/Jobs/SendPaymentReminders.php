<?php

namespace App\Jobs;

use App\Mail\PaymentReminder;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * Sends payment reminders for bookings due within the configured window.
 *
 * Deliberately NOT ShouldQueue. QUEUE_CONNECTION is "database" and no worker
 * runs on Vercel, so a queued dispatch of this job would be written to the
 * jobs table and never executed -- a silent no-op. Running inline keeps
 * ::dispatch() and the cron route honest, and lets both report what happened.
 */
class SendPaymentReminders
{
    use Dispatchable;

    /**
     * @return array{status: string, sent: int, failed: int, window: string}
     */
    public function handle(): array
    {
        if ((int) Setting::get('payment_reminder_enabled', '1') !== 1) {
            Log::info('Payment reminder run skipped: reminders are disabled.');

            return ['status' => 'disabled', 'sent' => 0, 'failed' => 0, 'window' => ''];
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

        $sent = 0;
        $failed = 0;

        foreach ($bookings as $booking) {
            $recipient = trim((string) $booking->customer_email);

            if ($recipient === '' || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $failed++;
                Log::warning("Payment reminder skipped for {$booking->booking_ref}: invalid email '{$recipient}'.");

                continue;
            }

            try {
                Mail::to($recipient)->send(new PaymentReminder($booking));
                $booking->update(['payment_reminder_sent_at' => now()]);
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                report($e);
                Log::error("Payment reminder failed for {$booking->booking_ref} <{$recipient}>: " . $e->getMessage());
            }
        }

        Log::info("Payment reminder run complete for {$bookings->count()} booking(s): {$sent} sent, {$failed} failed.");

        return [
            'status' => 'ok',
            'sent' => $sent,
            'failed' => $failed,
            'window' => $today->toDateString() . ' to ' . $today->copy()->addDays($days)->toDateString(),
        ];
    }
}
