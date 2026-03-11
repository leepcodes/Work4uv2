<?php

namespace App\Services;

use App\Models\Calendar;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    public function getSlots()
    {
        return Calendar::where('tutor_id', Auth::id())
            ->where('type', 'time_slot')
            ->get();
    }

    public function getDaysOff()
    {
        return Calendar::where('tutor_id', Auth::id())
            ->where('type', 'day_off')
            ->get();
    }

    public function addSlot(array $data)
    {
        return Calendar::create([
            'tutor_id'   => Auth::id(),
            'type'       => 'time_slot',
            'start_time' => $data['start_time'],
            'end_time'   => $data['end_time'],
        ]);
    }

    public function addDayOff(array $data)
    {
        return Calendar::create([
            'tutor_id'     => Auth::id(),
            'type'         => 'day_off',
            'day_off_date' => $data['day_off_date'],
            'reason'       => $data['reason'] ?? null,
        ]);
    }

    public function delete(int $id)
    {
        return Calendar::where('id', $id)
            ->where('tutor_id', Auth::id())
            ->delete();
    }
}