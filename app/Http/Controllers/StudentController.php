<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Services\TutorService;
use App\Services\SubjectService;
use App\Services\ScheduleService;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function __construct(
        protected TutorService $tutorService,
        protected StudentService $studentService,
        protected SubjectService $subjectService,
        protected ScheduleService $scheduleService
    ) {}

    public function index()
    {
        return Inertia::render('landingpage/student/studentlanding', [
            'tutors' => $this->studentService->getTutors(),
        ]);
    }

    public function profile(string $uuid)
    {
        $tutor    = $this->studentService->getTutorByUuid($uuid);
        $subjects = $this->subjectService->fetchSubjects($tutor->id); // ← $tutor->id not $tutor['id']

        return Inertia::render('landingpage/tutor/tutorprofile', [
            'tutor'    => $tutor,
            'subjects' => $subjects,
        ]);
    }
    public function tutorlist()
    {
        return Inertia::render('landingpage/student/mystudents');
    }

    public function tutorandsubj()
    {
        $classes = $this->scheduleService->getStudentClasses();

        return Inertia::render('landingpage/student/tutorsandsubject', [
            'classes' => $classes,
        ]);
    }
   
}