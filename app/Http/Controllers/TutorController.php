<?php

namespace App\Http\Controllers;

use App\Services\TutorService;
use App\Services\SubjectService;
use Inertia\Inertia;

class TutorController extends Controller
{
    public function __construct(
        protected TutorService $tutorService,
        protected SubjectService $subjectService
    ) {}

 
}