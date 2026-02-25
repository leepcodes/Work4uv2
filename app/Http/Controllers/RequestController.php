<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TutorVerificationRequest;
use App\Services\TutorService;
use Inertia\Inertia;

class RequestController extends Controller
{
   public function requests()
    {
        return Inertia::render('landingpage/tutor/request');
    }

}
