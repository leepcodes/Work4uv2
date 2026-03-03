<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\ScheduleService;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService,
    ) {}

    public function mytutor()
    {
        $classes = $this->scheduleService->getTutorList();

        return Inertia::render('landingpage/student/mytutor', [
            'classes' => $classes,
        ]);
    }

     public function mystudents()
    {
        $students = $this->scheduleService->getStudentList();

        return Inertia::render('landingpage/tutor/mystudents', [
            'students' => $students,
        ]);
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer',
            'tutor_id'           => 'required|integer',
            'total_class_count'  => 'required|integer|min:1',
            'tutor_custom_price' => 'required|numeric|min:0',
        ]);
 
        $this->scheduleService->enroll($validated);

        return back()->with('success', 'Enrolled successfully!');
    }
}