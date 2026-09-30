<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ResendNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function service(): Service
    {
        return Service::create([
            'name' => 'DUO',
            'price' => 699.00,
            'duration_minutes' => 20,
            'max_pax' => 2,
            'is_active' => true,
        ]);
    }

    private function booking(?string $email = 'johnchuquel@gmail.com'): Booking
    {
        return Booking::create([
            'service_id' => $this->service()->id,
            'customer_name' => 'John Chuquel',
            'customer_email' => $email ?? '',
            'customer_phone' => '09171234567',
            'num_pax' => 2,
            'booking_date' => Carbon::today()->addDay()->format('Y-m-d'),
            'booking_time' => '10:00',
            'total_amount' => 699.00,
            'amount_paid' => 699.00,
            'payment_method' => 'gcash',
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'agreed_to_policy' => true,
        ]);
    }

    public function test_guests_cannot_resend(): void
    {
        $booking = $this->booking();

        $this->post(route('admin.booking.resend', $booking))
            ->assertRedirect(route('login'));
    }

    public function test_it_sends_the_confirmation_to_the_booking_email(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);

        $booking = $this->booking();

        $this->actingAs($this->admin())
            ->postJson(route('admin.booking.resend', $booking))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'johnchuquel@gmail.com');

        Mail::assertSent(BookingConfirmation::class, function (BookingConfirmation $mail) use ($booking) {
            return $mail->hasTo('johnchuquel@gmail.com')
                && $mail->booking->is($booking);
        });
    }

    public function test_it_returns_a_422_when_the_booking_has_no_email(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);

        $booking = $this->booking('');

        $this->actingAs($this->admin())
            ->postJson(route('admin.booking.resend', $booking))
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        Mail::assertNothingSent();
    }

    public function test_it_returns_a_422_when_the_email_is_malformed(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);

        $booking = $this->booking('not-an-email');

        $this->actingAs($this->admin())
            ->postJson(route('admin.booking.resend', $booking))
            ->assertStatus(422)
            ->assertJsonPath('success', false);

        Mail::assertNothingSent();
    }

    public function test_it_reports_failure_instead_of_faking_success_on_the_log_driver(): void
    {
        Mail::fake();
        config(['mail.default' => 'log']);

        $booking = $this->booking();

        $this->actingAs($this->admin())
            ->postJson(route('admin.booking.resend', $booking))
            ->assertStatus(503)
            ->assertJsonPath('success', false);
    }

    public function test_it_surfaces_a_smtp_failure_as_a_502(): void
    {
        config(['mail.default' => 'smtp']);

        $booking = $this->booking();

        // Drive a real transport failure by pointing the smtp mailer at a dead host.
        config([
            'mail.mailers.smtp.host' => '127.0.0.1',
            'mail.mailers.smtp.port' => 1,
            'mail.mailers.smtp.timeout' => 1,
        ]);

        $response = $this->actingAs($this->admin())
            ->postJson(route('admin.booking.resend', $booking));

        $response->assertStatus(502)->assertJsonPath('success', false);
        $this->assertStringContainsString('Could not send the email', $response->json('message'));
    }

    public function test_the_bookings_page_wires_the_button_to_the_endpoint(): void
    {
        $this->booking();

        $html = $this->actingAs($this->admin())
            ->get(route('admin.bookings'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString("' + b.id + '/resend'", $html);
        $this->assertStringContainsString('id="bm-resend-btn"', $html);
        $this->assertStringNotContainsString(
            "fb.textContent = 'Notifications resent successfully.';",
            $html,
            'The resend handler must not hardcode a success message.'
        );
    }

    public function test_the_button_does_not_claim_channels_that_do_not_exist(): void
    {
        $this->booking();

        $html = $this->actingAs($this->admin())
            ->get(route('admin.bookings'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('Resend confirmation email', $html);
        $this->assertStringNotContainsString('Resend emails, SMS, and webhooks', $html);
    }
}
