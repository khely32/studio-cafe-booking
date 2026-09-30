<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;

class StudioSchedule
{
    public const SLOT_INTERVAL_MINUTES = 30;

    public const CLOSED_DAYS = [0];

    public const OPEN_HOURS = [
        6 => [9, 12],
    ];

    public const DEFAULT_HOURS = [10, 17];

    public static function hoursFor(string $date): array
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        return self::OPEN_HOURS[$dayOfWeek] ?? self::DEFAULT_HOURS;
    }

    public static function isClosed(string $date): bool
    {
        return in_array(Carbon::parse($date)->dayOfWeek, self::CLOSED_DAYS, true);
    }

    public static function hoursLabel(string $date): string
    {
        [$startHour, $endHour] = self::hoursFor($date);

        $format = function (int $hour): string {
            $suffix = $hour >= 12 ? 'PM' : 'AM';
            $display = $hour % 12 === 0 ? 12 : $hour % 12;

            return $display . ':00 ' . $suffix;
        };

        return $format($startHour) . ' - ' . $format($endHour);
    }

    public static function totalSlotCount(string $date): int
    {
        [$startHour, $endHour] = self::hoursFor($date);

        return (int) floor((($endHour - $startHour) * 60) / self::SLOT_INTERVAL_MINUTES);
    }

    /**
     * Build the slot list for a date. When $excludeBookingId is set, that booking's
     * own reservation is ignored so a booking can be moved onto its current slot.
     */
    public static function slotsFor(Service $service, string $date, ?int $excludeBookingId = null): array
    {
        if (self::isClosed($date)) {
            return [];
        }

        [$startHour, $endHour] = self::hoursFor($date);
        $duration = $service->duration_minutes ?: 30;

        // whereDate() is required: the `date` cast is persisted as "Y-m-d H:i:s", so a
        // plain equality check against "Y-m-d" silently matches nothing on SQLite.
        $booked = Booking::whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($excludeBookingId, fn($q) => $q->whereKeyNot($excludeBookingId))
            ->pluck('booking_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->all();

        $slots = [];
        $current = Carbon::parse("{$date} {$startHour}:00");
        $closing = Carbon::parse("{$date} {$endHour}:00");

        while ($current->copy()->addMinutes($duration)->lte($closing)) {
            $timeStr = $current->format('H:i');
            $endSlot = $current->copy()->addMinutes($duration);

            $slots[] = [
                'time' => $timeStr,
                'display' => $current->format('g:i A'),
                'end_display' => $endSlot->format('g:i A'),
                'available' => !in_array($timeStr, $booked, true),
            ];

            $current->addMinutes(self::SLOT_INTERVAL_MINUTES);
        }

        return $slots;
    }
}
