<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService) {}

    public function index()
    {
        return Inertia::render('landingpage/student/studentlanding', [
            'tutors' => $this->studentService->getTutors(),
        ]);
    }
}