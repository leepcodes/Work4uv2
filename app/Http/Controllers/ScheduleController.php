<?php

namespace App\Http\Controllers;

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

     public function mystudent()
    {
        $students = $this->scheduleService->getTutorStudents();

        return Inertia::render('landingpage/tutor/mystudents', [
            'students' => $students,
        ]);
    }
}