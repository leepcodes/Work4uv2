<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function __construct(protected CalendarService $calendarService) {}

    public function index()
    {
       return Inertia::render('landingpage/tutor/calendar', [
            'timeSlots' => $this->calendarService->getSlots(),
            'daysOff'   => $this->calendarService->getDaysOff(),
    ]);
    }
  
    public function addSlot(Request $request)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $this->calendarService->addSlot($request->only('start_time', 'end_time'));

        return back();
    }

   public function addDayOff(Request $request)
{
    $request->validate([
        'day_off_date' => 'required|string', // ← was 'required|date'
    ]);

    $this->calendarService->addDayOff($request->only('day_off_date', 'reason'));

    return back();
}

    public function destroy(int $id)
    {
        $this->calendarService->delete($id);

        return back();
    }
}