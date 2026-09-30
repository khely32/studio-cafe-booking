<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingsTableScrollTest extends TestCase
{
    use RefreshDatabase;

    private function seedBooking(): Booking
    {
        $service = Service::create([
            'name' => 'DUO',
            'price' => 699.00,
            'duration_minutes' => 20,
            'max_pax' => 2,
            'is_active' => true,
        ]);

        return Booking::create([
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
            'status' => 'undecided',
            'agreed_to_policy' => true,
        ]);
    }

    private function html(): string
    {
        $this->seedBooking();

        return $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.bookings'))
            ->assertOk()
            ->getContent();
    }

    public function test_the_table_container_scrolls_vertically(): void
    {
        $html = $this->html();

        $this->assertMatchesRegularExpression(
            '/\.bk-table-wrap\s*\{[^}]*max-height\s*:/',
            $html,
            'The table container needs a max-height to become a scroll container.'
        );
        $this->assertMatchesRegularExpression(
            '/\.bk-table-wrap\s*\{[^}]*overflow-y\s*:\s*auto/',
            $html,
            'The table container needs overflow-y:auto.'
        );
    }

    public function test_the_container_no_longer_clips_with_overflow_hidden(): void
    {
        // overflow:hidden on the wrap was what cropped the dropdown in the first
        // place, so it must not come back on the base rule.
        $this->assertDoesNotMatchRegularExpression(
            '/\.bk-table-wrap\s*\{[^}]*overflow\s*:\s*hidden/',
            $this->html(),
            'overflow:hidden on the table container clips the action menu.'
        );
    }

    public function test_the_table_header_is_sticky(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.bk-table thead th\s*\{[^}]*position\s*:\s*sticky[^}]*top\s*:\s*0/',
            $this->html(),
            'The thead cells must be sticky at the top of the scroll container.'
        );
        $this->assertMatchesRegularExpression(
            '/\.bk-table thead th\s*\{[^}]*z-index\s*:/',
            $this->html(),
            'Sticky headers need a z-index to sit above scrolling rows.'
        );
    }

    public function test_the_action_menu_is_portalled_to_the_body(): void
    {
        $html = $this->html();

        $this->assertStringContainsString('document.body.appendChild(drop)', $html);
        $this->assertStringContainsString('cloneNode(true)', $html);
        $this->assertMatchesRegularExpression(
            '/\.bk-menu-drop\.is-fixed\s*\{[^}]*position\s*:\s*fixed/',
            $html,
            'The portalled menu must be viewport-positioned, otherwise the scroll container clips it.'
        );
    }

    public function test_the_menu_flips_above_the_trigger_near_the_bottom(): void
    {
        $html = $this->html();

        $this->assertStringContainsString('positionRowMenu', $html);
        $this->assertMatchesRegularExpression(
            '/if\s*\(top\s*\+\s*h\s*>\s*vh\s*-\s*pad\)/',
            $html,
            'The menu must flip when it would overflow the bottom of the viewport.'
        );
        $this->assertStringContainsString('r.top - h - gap', $html);
    }

    public function test_the_menu_dismisses_on_scroll_away_escape_and_outside_click(): void
    {
        $html = $this->html();

        $this->assertStringContainsString('syncRowMenu', $html);
        $this->assertStringContainsString("addEventListener('scroll'", $html);
        $this->assertStringContainsString('closeRowMenus', $html);
        $this->assertStringContainsString(
            "!e.target.closest('.bk-menu-drop')",
            $html,
            'Clicks inside the portalled menu must not be treated as outside clicks.'
        );
    }

    public function test_menu_actions_still_dismiss_the_menu(): void
    {
        $html = $this->html();

        // Each menu action opens a modal or posts an update; each must clear
        // the portalled node, not just drop the .open class.
        foreach (['openBookingModal', 'setBookingStatus', 'openReschedule'] as $fn) {
            $this->assertMatchesRegularExpression(
                '/function\s+' . $fn . '\s*\([^)]*\)\s*\{\s*closeRowMenus\(\);/',
                $html,
                "{$fn}() must dismiss the portalled action menu."
            );
        }
    }
}
