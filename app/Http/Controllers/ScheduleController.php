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

    public function studentSubjects(string $uuid)
    {
        $data = $this->scheduleService->getStudentWithSubjects($uuid);

        return Inertia::render('landingpage/tutor/studentsubj', $data);
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

    public function packageClasses(string $uuid)
    {
        $data = $this->scheduleService->getPackageClasses($uuid);

        return Inertia::render('landingpage/tutor/packageclasses', [
            'student' => $data['student'],
            'subject' => $data['subject'],
            'package' => $data['schedule'],
            'classes' => $data['classes'],
        ]);
    }

    public function tutorandsubj(string $uuid)
    {
        $data = $this->scheduleService->getStudentSubjectsAndPackages($uuid);

        return Inertia::render('landingpage/student/tutorsandsubject', [
            'tutor'    => $data['tutor'],
            'subjects' => $data['subjects'],
        ]);
    }
}