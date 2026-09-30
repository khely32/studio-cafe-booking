<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RebookLinkTest extends TestCase
{
    use RefreshDatabase;

    private function service(): Service
    {
        return Service::create([
            'name' => 'SELFIE',
            'description' => '1 pax',
            'price' => 399.00,
            'duration_minutes' => 10,
            'max_pax' => 1,
            'is_active' => true,
        ]);
    }

    private function booking(): Booking
    {
        return Booking::create([
            'service_id' => $this->service()->id,
            'customer_name' => 'John Chuquel',
            'customer_email' => 'johnchuquel@gmail.com',
            'customer_phone' => '09171234567',
            'num_pax' => 1,
            'booking_date' => Carbon::today()->addDay()->format('Y-m-d'),
            'booking_time' => '10:00',
            'total_amount' => 399.00,
            'amount_paid' => 0,
            'payment_method' => 'gcash',
            'payment_status' => 'pending',
            'status' => 'confirmed',
            'agreed_to_policy' => true,
        ]);
    }

    private function adminHtml(): string
    {
        $this->booking();

        return $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.bookings'))
            ->assertOk()
            ->getContent();
    }

    public function test_the_rebook_link_does_not_point_at_the_json_endpoint(): void
    {
        $html = $this->adminHtml();

        // route('booking.service', ...) is GET /booking/service/{id}, which
        // returns raw JSON. Clicking Rebook used to land the admin there.
        $this->assertStringNotContainsString(
            '/booking/service/',
            $html,
            'Rebook must not navigate to the showService JSON endpoint.'
        );
    }

    public function test_the_rebook_link_points_at_the_booking_page_with_the_service(): void
    {
        $html = $this->adminHtml();

        $expected = route('booking.index', ['service' => 1]);

        $this->assertStringContainsString($expected, $html);
        $this->assertStringContainsString('href="' . $expected . '"', $html);
    }

    public function test_the_modal_rebook_button_uses_the_same_fixed_url(): void
    {
        $html = $this->adminHtml();

        $this->assertSame(
            route('booking.index', ['service' => 1]),
            $this->bookingData($html)[0]['serviceUrl'],
            'The modal Rebook button must resolve to the booking page, not the JSON endpoint.'
        );

        $this->assertStringContainsString(
            "document.getElementById('bm-rebook').href = b.serviceUrl;",
            $html
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function bookingData(string $html): array
    {
        $matched = preg_match(
            '/window\.__bookingData = (.*);<\/script>/',
            $html,
            $m
        );

        $this->assertSame(1, $matched, 'Could not find the embedded booking data.');

        return json_decode($m[1], true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_the_booking_page_preselects_a_service_from_the_query_string(): void
    {
        $this->service();

        $html = $this->get(route('booking.index', ['service' => 1]))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString("get('service')", $html);
        $this->assertStringContainsString('.pkg-card[data-service-id="', $html);
        $this->assertStringContainsString('card.click()', $html);
    }

    public function test_the_preselect_selector_is_guarded_against_malformed_input(): void
    {
        $this->service();

        $html = $this->get(route('booking.index'))->getContent();

        // The raw param is interpolated into a querySelector attribute value,
        // so a non-numeric value must be rejected before it is used.
        $this->assertStringContainsString('/^\d+$/.test(requested)', $html);
    }

    public function test_the_booking_page_lists_the_package_with_a_service_id(): void
    {
        $this->service();

        $html = $this->get(route('booking.index'))->assertOk()->getContent();

        $this->assertStringContainsString('data-service-id="1"', $html);
    }
}
