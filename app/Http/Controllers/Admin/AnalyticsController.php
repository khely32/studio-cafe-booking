<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $overview = [
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'completed'])->sum('amount_paid'),
            'today_bookings' => Booking::where('booking_date', $today)->count(),
            'month_bookings' => Booking::where('booking_date', '>=', $startOfMonth)->count(),
            'month_revenue' => Booking::where('booking_date', '>=', $startOfMonth)->whereIn('status', ['confirmed', 'completed'])->sum('amount_paid'),
            'year_revenue' => Booking::where('booking_date', '>=', $startOfYear)->whereIn('status', ['confirmed', 'completed'])->sum('amount_paid'),
            'avg_booking_value' => Booking::whereIn('status', ['confirmed', 'completed'])->avg('total_amount'),
        ];

        // Bookings by status
        $statusBreakdown = Booking::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Expenses
        $totalExpenses = Expense::sum('amount');
        $monthExpenses = Expense::where('expense_date', '>=', $startOfMonth)->sum('amount');
        $yearExpenses = Expense::where('expense_date', '>=', $startOfYear)->sum('amount');

        $netProfit = max(0, $overview['total_revenue'] - $totalExpenses);
        $monthNetProfit = max(0, $overview['month_revenue'] - $monthExpenses);
        $yearNetProfit = max(0, $overview['year_revenue'] - $yearExpenses);

        // Monthly expenses (last 12 months)
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyExpenses[] = [
                'month' => $month->format('M Y'),
                'expenses' => round(Expense::whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount'), 2),
            ];
        }

        // Expenses by category
        $expensesByCategory = Expense::selectRaw('category, sum(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Top selling products (packages + add-ons)
        $serviceSales = Booking::join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name, count(*) as units, sum(bookings.amount_paid) as revenue')
            ->whereIn('bookings.status', ['confirmed', 'completed'])
            ->groupBy('services.name')
            ->get()
            ->map(fn($row) => [
                'name' => $row->name,
                'type' => 'Package',
                'units' => (int) $row->units,
                'revenue' => round((float) $row->revenue, 2),
            ]);

        $addonSales = DB::table('addon_booking')
            ->join('addons', 'addons.id', '=', 'addon_booking.addon_id')
            ->join('bookings', 'bookings.id', '=', 'addon_booking.booking_id')
            ->whereIn('bookings.status', ['confirmed', 'completed'])
            ->selectRaw('addons.name, sum(addon_booking.quantity) as units, sum(addon_booking.quantity * addon_booking.price_at_time) as revenue')
            ->groupBy('addons.name')
            ->get()
            ->map(fn($row) => [
                'name' => $row->name,
                'type' => 'Add-On',
                'units' => (int) $row->units,
                'revenue' => round((float) $row->revenue, 2),
            ]);

        $topProducts = $serviceSales->concat($addonSales)
            ->sortByDesc('units')
            ->values()
            ->take(8);

        // Bookings by package
        $packageBreakdown = Booking::join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name, count(*) as count, sum(bookings.amount_paid) as revenue')
            ->whereIn('bookings.status', ['confirmed', 'completed'])
            ->groupBy('services.name')
            ->orderByDesc('count')
            ->get();

        // Monthly bookings (last 12 months)
        $monthlyBookings = [];
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $count = Booking::whereBetween('booking_date', [$monthStart, $monthEnd])->count();
            $revenue = Booking::whereBetween('booking_date', [$monthStart, $monthEnd])
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum('amount_paid');

            $monthlyBookings[] = ['month' => $month->format('M Y'), 'count' => $count];
            $monthlyRevenue[] = ['month' => $month->format('M Y'), 'revenue' => round($revenue, 2)];
        }

        // Popular time slots
        $popularTimes = Booking::whereIn('status', ['confirmed', 'completed'])
            ->selectRaw('booking_time, count(*) as count')
            ->groupBy('booking_time')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(fn($row) => [
                'time' => Carbon::parse($row->booking_time)->format('g:i A'),
                'count' => $row->count,
            ]);

        // Bookings by day of week.
        // Production runs PostgreSQL (Neon) while local dev is MySQL, so
        // DAYOFWEEK() is not portable. Group by the plain date and derive the
        // weekday in PHP instead: booking_date is a DATE column, so the number
        // of groups stays small.
        $countsByDate = Booking::selectRaw('booking_date, count(*) as count')
            ->groupBy('booking_date')
            ->pluck('count', 'booking_date');

        $countsByWeekday = array_fill(0, 7, 0);
        foreach ($countsByDate as $date => $count) {
            $countsByWeekday[Carbon::parse($date)->dayOfWeek] += (int) $count;
        }

        $dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $bookingsByDay = collect($dayLabels)->map(function ($label, $index) use ($countsByWeekday) {
            // Carbon::dayOfWeek is 0=Sunday, which lines up with $dayLabels.
            return ['day' => $label, 'count' => $countsByWeekday[$index]];
        })->toArray();

        return view('admin.analytics.index', compact(
            'overview', 'statusBreakdown', 'packageBreakdown',
            'monthlyBookings', 'monthlyRevenue', 'popularTimes', 'bookingsByDay',
            'totalExpenses', 'monthExpenses', 'yearExpenses',
            'netProfit', 'monthNetProfit', 'yearNetProfit',
            'monthlyExpenses', 'expensesByCategory', 'topProducts'
        ))->with('user', auth()->user());
    }
}
