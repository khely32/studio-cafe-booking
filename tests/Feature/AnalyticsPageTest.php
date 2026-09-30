<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Expense;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsPageTest extends TestCase
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

    private function bookingOn(string $date): Booking
    {
        return Booking::create([
            'service_id' => $this->service()->id,
            'customer_name' => 'John Chuquel',
            'customer_email' => 'johnchuquel@gmail.com',
            'customer_phone' => '09171234567',
            'num_pax' => 1,
            'booking_date' => $date,
            'booking_time' => '10:00',
            'total_amount' => 399.00,
            'amount_paid' => 399.00,
            'payment_method' => 'gcash',
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'agreed_to_policy' => true,
        ]);
    }

    public function test_the_analytics_page_renders(): void
    {
        $this->bookingOn(Carbon::today()->addDay()->format('Y-m-d'));

        Expense::create([
            'title' => 'Rent',
            'category' => 'Overhead',
            'amount' => 5000,
            'expense_date' => Carbon::today()->format('Y-m-d'),
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.analytics'))
            ->assertOk()
            ->assertSee('Analytics');
    }

    public function test_the_analytics_page_renders_with_no_data_at_all(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.analytics'))
            ->assertOk();
    }

    /**
     * Regression: the day-of-week chart used MySQL's DAYOFWEEK(), which does
     * not exist in PostgreSQL (production) and 500'd the whole page. Deriving
     * the weekday in PHP keeps the query portable.
     */
    public function test_bookings_by_day_of_week_buckets_each_date_correctly(): void
    {
        // 2026-09-27 is a Sunday, 2026-09-28 a Monday.
        $this->bookingOn('2026-09-27');
        $this->bookingOn('2026-09-28');
        $this->bookingOn('2026-09-28');

        $controller = new \App\Http\Controllers\Admin\AnalyticsController();

        $view = $controller->index();
        $data = $view->getData();

        $byDay = collect($data['bookingsByDay'])->keyBy('day');

        $this->assertSame(1, $byDay['Sun']['count']);
        $this->assertSame(2, $byDay['Mon']['count']);
        $this->assertSame(0, $byDay['Tue']['count']);
        $this->assertSame(0, $byDay['Sat']['count']);
    }
}
