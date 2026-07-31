<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function homepage()
    {
        $policy = Setting::get('home_policy');
        $guides = Setting::get('home_guides');
        return view('admin.settings.homepage', compact('policy', 'guides'));
    }

    public function updateHomepage(Request $request)
    {
        $validated = $request->validate([
            'home_policy' => 'nullable|string',
            'home_guides' => 'nullable|string',
        ]);

        Setting::set('home_policy', $validated['home_policy'] ?? '');
        Setting::set('home_guides', $validated['home_guides'] ?? '');

        return redirect()->route('admin.settings.homepage')->with('success', 'Homepage policy & guides updated successfully.');
    }

    public function reminders()
    {
        $enabled = (int) Setting::get('payment_reminder_enabled', '1') === 1;
        $days = (int) Setting::get('payment_reminder_days', '3');

        $bookings = Booking::with('service')
            ->whereIn('payment_status', ['pending', 'partial'])
            ->orderByDesc('booking_date')
            ->get();

        return view('admin.settings.reminders', compact('enabled', 'days', 'bookings'));
    }

    public function updateReminders(Request $request)
    {
        $validated = $request->validate([
            'payment_reminder_enabled' => 'boolean',
            'payment_reminder_days' => 'required|integer|between:1,30',
        ]);

        Setting::set('payment_reminder_enabled', $request->boolean('payment_reminder_enabled') ? '1' : '0');
        Setting::set('payment_reminder_days', (string) $validated['payment_reminder_days']);

        return redirect()->route('admin.settings.reminders')->with('success', 'Reminder settings saved.');
    }

    public function runReminders()
    {
        if ((int) Setting::get('payment_reminder_enabled', '1') !== 1) {
            return redirect()->route('admin.settings.reminders')
                ->with('warning', 'Reminders are currently disabled. Enable them first.');
        }

        $days = max(1, (int) Setting::get('payment_reminder_days', '3'));
        $today = Carbon::today();

        $count = Booking::whereIn('payment_status', ['pending', 'partial'])
            ->whereNull('payment_reminder_sent_at')
            ->where('booking_date', '>=', $today)
            ->where('booking_date', '<=', $today->copy()->addDays($days))
            ->whereNotNull('customer_email')
            ->where('customer_email', '!=', '')
            ->count();

        \App\Jobs\SendPaymentReminders::dispatchSync();

        return redirect()->route('admin.settings.reminders')
            ->with('success', "Payment reminder run complete — sent to {$count} booking(s).");
    }
}
