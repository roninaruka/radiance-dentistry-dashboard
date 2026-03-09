<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\BlockedSlot;
use Carbon\Carbon;

class BookingService
{
    public function getAvailableSlots($date)
    {
        $carbonDate = Carbon::parse($date);
        
        // Monday to Saturday only (1 to 6)
        if ($carbonDate->isSunday()) {
            return [];
        }

        $slots = [];
        $startTime = Carbon::createFromTimeString('09:00:00');
        $endTime = Carbon::createFromTimeString('19:00:00');

        // Get blocked segments
        $blockedSegments = BlockedSlot::where('date', $date)->get();
        if ($blockedSegments->contains('is_full_day', true)) {
            return [];
        }

        // Get existing appointments (confirmed)
        $bookedSlots = Appointment::where('appointment_date', $date)
            ->whereIn('status', ['confirmed'])
            ->pluck('appointment_time')
            ->map(fn($time) => Carbon::parse($time)->format('H:i'))
            ->toArray();

        $currentSlot = $startTime->copy();
        while ($currentSlot < $endTime) {
            $slotString = $currentSlot->format('H:i');
            
            $isBlocked = false;
            foreach ($blockedSegments as $segment) {
                if ($segment->start_time && $segment->end_time) {
                    $start = Carbon::parse($segment->start_time);
                    $end = Carbon::parse($segment->end_time);
                    if ($currentSlot >= $start && $currentSlot < $end) {
                        $isBlocked = true;
                        break;
                    }
                }
            }

            if (!$isBlocked && !in_array($slotString, $bookedSlots)) {
                // Also check if slot is in the future if date is today
                if (!$carbonDate->isToday() || $currentSlot->isAfter(now())) {
                    $slots[] = $slotString;
                }
            }

            $currentSlot->addMinutes(30);
        }

        return $slots;
    }
}
