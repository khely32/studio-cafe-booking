<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BookingRescheduleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function service(int $duration = 20): Service
    {
        return Service::create([
            'name' => 'DUO',
            'description' => '1 to 2 pax',
            'price' => 699.00,
            'duration_minutes' => $duration,
            'max_pax' => 2,
            'is_active' => true,
        ]);
    }

    private function booking(Service $service, string $date, string $time, string $status = 'confirmed'): Booking
    {
        return Booking::create([
            'service_id' => $service->id,
            'customer_name' => 'Test Customer',
            'customer_email' => 'test@example.com',
            'customer_phone' => '09171234567',
            'num_pax' => 2,
            'booking_date' => $date,
            'booking_time' => $time,
            'total_amount' => 699.00,
            'amount_paid' => 699.00,
            'payment_method' => 'gcash',
            'payment_status' => 'paid',
            'status' => $status,
            'agreed_to_policy' => true,
        ]);
    }

    /** Next weekday (never Sunday) at least a day out. */
    private function targetDate(): string
    {
        $date = Carbon::today()->addDay();

        while ($date->dayOfWeek === 0) {
            $date->addDay();
        }

        return $date->format('Y-m-d');
    }

    public function test_guests_are_redirected_away_from_reschedule(): void
    {
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');

        $this->patch(route('admin.booking.reschedule', $booking), [
            'booking_date' => $this->targetDate(),
            'booking_time' => '11:00',
        ])->assertRedirect(route('login'));

        $this->assertSame('10:00', $booking->fresh()->booking_time);
    }

    public function test_admin_can_reschedule_to_an_open_slot(): void
    {
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');

        $response = $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $this->targetDate(),
                'booking_time' => '11:00',
            ]);

        $response->assertOk()->assertJsonPath('success', true);
        $this->assertSame('11:00', $booking->fresh()->booking_time);
    }

    public function test_reschedule_rejects_a_slot_held_by_another_booking(): void
    {
        $service = $this->service();
        $date = $this->targetDate();
        $mover = $this->booking($service, $date, '10:00');
        $this->booking($service, $date, '11:00');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $mover), [
                'booking_date' => $date,
                'booking_time' => '11:00',
            ])
            ->assertStatus(409);

        $this->assertSame('10:00', $mover->fresh()->booking_time);
    }

    public function test_a_booking_may_be_rescheduled_onto_its_own_current_slot(): void
    {
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');

        $slots = $this->actingAs($this->admin())
            ->getJson(route('admin.booking.slots', ['booking' => $booking, 'date' => $this->targetDate()]));

        $slots->assertOk();

        $tenOClock = collect($slots->json('slots'))->firstWhere('time', '10:00');
        $this->assertNotNull($tenOClock);
        $this->assertTrue($tenOClock['available'], 'A booking must not block its own slot.');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $this->targetDate(),
                'booking_time' => '10:00',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_reschedule_rejects_a_past_date(): void
    {
        $booking = $this->booking($this->service(), Carbon::today()->format('Y-m-d'), '10:00');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => Carbon::today()->subDay()->format('Y-m-d'),
                'booking_time' => '11:00',
            ])
            ->assertStatus(422);

        $this->assertSame(Carbon::today()->format('Y-m-d'), $booking->fresh()->booking_date->format('Y-m-d'));
    }

    public function test_reschedule_rejects_sundays(): void
    {
        $sunday = Carbon::today()->next(Carbon::SUNDAY);
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $sunday->format('Y-m-d'),
                'booking_time' => '11:00',
            ])
            ->assertStatus(422);

        $this->assertSame('10:00', $booking->fresh()->booking_time);
    }

    public function test_reschedule_rejects_a_time_outside_studio_hours(): void
    {
        $date = $this->targetDate();
        $booking = $this->booking($this->service(), $date, '10:00');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $date,
                'booking_time' => '22:00',
            ])
            ->assertStatus(422);

        $this->assertSame('10:00', $booking->fresh()->booking_time);
    }

    public function test_cancelled_bookings_do_not_block_a_slot(): void
    {
        $service = $this->service();
        $date = $this->targetDate();
        $mover = $this->booking($service, $date, '10:00');
        $this->booking($service, $date, '11:00', 'cancelled');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $mover), [
                'booking_date' => $date,
                'booking_time' => '11:00',
            ])
            ->assertOk();

        $this->assertSame('11:00', $mover->fresh()->booking_time);
    }

    public function test_slots_endpoint_returns_no_slots_on_sunday(): void
    {
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');
        $sunday = Carbon::today()->next(Carbon::SUNDAY)->format('Y-m-d');

        $this->actingAs($this->admin())
            ->getJson(route('admin.booking.slots', ['booking' => $booking, 'date' => $sunday]))
            ->assertOk()
            ->assertJsonPath('slots', []);
    }

    public function test_reschedule_stays_within_closing_time_for_longer_services(): void
    {
        // 20 minute service, 10:00-17:00 day: slots step by 30 min and must finish
        // before closing, so the last bookable start is 16:30.
        $date = $this->targetDate();
        $booking = $this->booking($this->service(20), $date, '10:00');

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $date,
                'booking_time' => '16:30',
            ])
            ->assertOk();

        $this->assertSame('16:30', $booking->fresh()->booking_time);

        $this->actingAs($this->admin())
            ->patchJson(route('admin.booking.reschedule', $booking), [
                'booking_date' => $date,
                'booking_time' => '16:40',
            ])
            ->assertStatus(422);
    }

    public function test_public_slot_lookup_marks_existing_bookings_as_taken(): void
    {
        // Regression: booking_date is stored as "Y-m-d H:i:s", so an equality check
        // against "Y-m-d" matched nothing and let customers double-book a slot.
        $service = $this->service();
        $date = $this->targetDate();
        $this->booking($service, $date, '11:00');

        $response = $this->getJson(route('booking.slots', [
            'service_id' => $service->id,
            'date' => $date,
        ]));

        $response->assertOk();

        $eleven = collect($response->json('slots'))->firstWhere('time', '11:00');
        $this->assertNotNull($eleven);
        $this->assertFalse($eleven['available'], 'An already-booked slot must not be offered.');

        $ten = collect($response->json('slots'))->firstWhere('time', '10:00');
        $this->assertTrue($ten['available']);
    }

    public function test_public_calendar_reports_booked_counts(): void
    {
        $service = $this->service();
        $date = $this->targetDate();
        $this->booking($service, $date, '11:00');
        $this->booking($service, $date, '11:30');

        $response = $this->getJson(route('booking.calendar', [
            'service_id' => $service->id,
            'month' => Carbon::parse($date)->format('Y-m-01'),
        ]));

        $response->assertOk();

        $day = collect($response->json('dates'))->firstWhere('date', $date);
        $this->assertNotNull($day);
        $this->assertSame(2, $day['booked'], 'Calendar must count existing bookings for the day.');
    }

    public function test_date_range_filter_returns_bookings_on_the_end_date(): void
    {
        $date = $this->targetDate();
        $this->booking($this->service(), $date, '10:00');

        $response = $this->actingAs($this->admin())
            ->get(route('admin.bookings', [
                'filter' => 'upcoming',
                'date_from' => $date,
                'date_to' => $date,
            ]));

        $response->assertOk();
        $this->assertSame(1, Booking::count());

        $bookings = $response->viewData('bookings');
        $this->assertCount(1, $bookings, 'A booking on the range end date must be included.');
    }

    public function test_bookings_page_wires_reschedule_to_the_dialog(): void
    {
        $booking = $this->booking($this->service(), $this->targetDate(), '10:00');

        $html = $this->actingAs($this->admin())
            ->get(route('admin.bookings'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('openReschedule(' . $booking->id, $html);
        $this->assertStringContainsString('id="rsModal"', $html);
        $this->assertStringNotContainsString(
            "bm-reschedule').href",
            $html,
            'Reschedule must not be wired to the service JSON URL any more.'
        );
    }
}
