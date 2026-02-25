<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TutorVerificationRequest;
use App\Services\TutorService;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function subject()
    {
        return Inertia::render('landingpage/tutor/mysubject');
    }

     public function createsubject()
    {
        return Inertia::render('landingpage/tutor/createsubject');
    }

}
