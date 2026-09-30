<?php

namespace Tests\Feature;

use App\Jobs\SendPaymentReminders;
use App\Mail\PaymentReminder;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentReminderCronTest extends TestCase
{
    use RefreshDatabase;

    private function pendingBooking(array $overrides = []): Booking
    {
        $service = Service::create([
            'name' => 'DUO',
            'price' => 699.00,
            'duration_minutes' => 20,
            'max_pax' => 2,
            'is_active' => true,
        ]);

        return Booking::create(array_merge([
            'service_id' => $service->id,
            'customer_name' => 'John Chuquel',
            'customer_email' => 'johnchuquel@gmail.com',
            'customer_phone' => '09171234567',
            'num_pax' => 2,
            'booking_date' => Carbon::today()->addDay()->format('Y-m-d'),
            'booking_time' => '10:00',
            'total_amount' => 699.00,
            'amount_paid' => 0,
            'payment_method' => 'gcash',
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'agreed_to_policy' => true,
        ], $overrides));
    }

    /*
     * Regression: the old guard was "if ($secret) { verify }", so with no
     * secret configured the endpoint skipped verification entirely. A plain
     * GET from anyone on the internet would then email every unpaid customer.
     */

    public function test_it_rejects_the_request_when_no_secret_is_configured(): void
    {
        Mail::fake();
        config(['services.cron.secret' => null]);
        $this->pendingBooking();

        $this->get(route('cron.payment-reminders'))->assertStatus(503);

        Mail::assertNothingSent();
    }

    public function test_it_rejects_a_request_with_no_authorization_header(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        $this->pendingBooking();

        $this->get(route('cron.payment-reminders'))->assertStatus(403);

        Mail::assertNothingSent();
    }

    public function test_it_rejects_a_wrong_secret(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        $this->pendingBooking();

        $this->get(route('cron.payment-reminders'), ['Authorization' => 'Bearer wrong'])
            ->assertStatus(403);

        Mail::assertNothingSent();
    }

    public function test_the_backup_endpoint_is_also_protected(): void
    {
        config(['services.cron.secret' => null]);
        $this->get(route('cron.backup-db'))->assertStatus(503);

        config(['services.cron.secret' => 'super-secret']);
        $this->get(route('cron.backup-db'))->assertStatus(403);
        $this->get(route('cron.backup-db'), ['Authorization' => 'Bearer wrong'])->assertStatus(403);
    }

    public function test_a_valid_cron_request_sends_the_reminders_and_reports_counts(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        Setting::set('payment_reminder_enabled', '1');
        Setting::set('payment_reminder_days', '3');

        $booking = $this->pendingBooking();

        $this->get(route('cron.payment-reminders'), ['Authorization' => 'Bearer super-secret'])
            ->assertOk()
            ->assertJsonPath('status', 'ok')
            ->assertJsonPath('sent', 1)
            ->assertJsonPath('failed', 0);

        Mail::assertSent(PaymentReminder::class, fn (PaymentReminder $mail) => $mail->hasTo('johnchuquel@gmail.com'));
        $this->assertNotNull($booking->fresh()->payment_reminder_sent_at);
    }

    public function test_it_does_not_remind_the_same_booking_twice(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        Setting::set('payment_reminder_enabled', '1');

        $booking = $this->pendingBooking();

        $headers = ['Authorization' => 'Bearer super-secret'];
        $this->get(route('cron.payment-reminders'), $headers)->assertJsonPath('sent', 1);
        $this->get(route('cron.payment-reminders'), $headers)->assertJsonPath('sent', 0);

        Mail::assertSentCount(1);
    }

    public function test_it_skips_bookings_with_an_invalid_email_and_counts_them_as_failed(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        Setting::set('payment_reminder_enabled', '1');

        $this->pendingBooking(['customer_email' => 'not-an-email']);

        $this->get(route('cron.payment-reminders'), ['Authorization' => 'Bearer super-secret'])
            ->assertOk()
            ->assertJsonPath('sent', 0)
            ->assertJsonPath('failed', 1);

        Mail::assertNothingSent();
    }

    public function test_it_respects_the_disabled_setting(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        Setting::set('payment_reminder_enabled', '0');

        $this->pendingBooking();

        $this->get(route('cron.payment-reminders'), ['Authorization' => 'Bearer super-secret'])
            ->assertOk()
            ->assertJsonPath('status', 'disabled');

        Mail::assertNothingSent();
    }

    public function test_it_ignores_paid_bookings_and_dates_outside_the_window(): void
    {
        Mail::fake();
        config(['services.cron.secret' => 'super-secret']);
        Setting::set('payment_reminder_enabled', '1');
        Setting::set('payment_reminder_days', '3');

        $this->pendingBooking(['payment_status' => 'paid']);
        $this->pendingBooking(['booking_date' => Carbon::today()->addDays(30)->format('Y-m-d')]);
        $this->pendingBooking(['booking_date' => Carbon::today()->subDay()->format('Y-m-d')]);

        $this->get(route('cron.payment-reminders'), ['Authorization' => 'Bearer super-secret'])
            ->assertJsonPath('sent', 0);

        Mail::assertNothingSent();
    }

    public function test_an_admin_can_still_run_reminders_manually(): void
    {
        Mail::fake();
        Setting::set('payment_reminder_enabled', '1');

        $this->pendingBooking();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('admin.settings.reminders.run'))
            ->assertRedirect(route('admin.settings.reminders'))
            ->assertSessionHas('success');

        Mail::assertSent(PaymentReminder::class);
    }

    public function test_a_guest_cannot_run_reminders_manually(): void
    {
        Setting::set('payment_reminder_enabled', '1');
        $this->pendingBooking();

        $this->post(route('admin.settings.reminders.run'))->assertRedirect(route('login'));
    }

    public function test_the_job_is_not_queueable(): void
    {
        // QUEUE_CONNECTION is "database" with no worker on Vercel, so a queued
        // dispatch would sit in the jobs table forever and never send anything.
        $this->assertNotContains(
            \Illuminate\Contracts\Queue\ShouldQueue::class,
            class_implements(SendPaymentReminders::class),
            'SendPaymentReminders must not be ShouldQueue or it can silently never run.'
        );
    }
}
