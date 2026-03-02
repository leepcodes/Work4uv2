<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService,
    ) {}

    public function myclasses()
    {
        $classes = $this->scheduleService->getStudentClasses();

        return Inertia::render('landingpage/student/myclasses', [
            'classes' => $classes,
        ]);
    }
}