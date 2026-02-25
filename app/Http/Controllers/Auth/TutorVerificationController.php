<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TutorVerificationRequest;
use App\Services\TutorService;
use Inertia\Inertia;

class TutorVerificationController extends Controller
{
    public function __construct(protected TutorService $tutorService) {}

  
    public function index()
    {
        $step = auth()->user()->verification_step ?? 0;

        return match (true) {
            $step >= 3  => redirect()->route('tutor.landing'),
            $step === 2 => redirect()->route('tutor.verification-3'),
            $step === 1 => redirect()->route('tutor.verification-2'),
            default     => redirect()->route('tutor.verification-1'),
        };
    }

    

   
    public function landing()
    {
        return Inertia::render('landingpage/tutor/tutorlanding');
    }

    public function step1()
    {
        return Inertia::render('landingpage/tutor/step1', [
            'user' => auth()->user()->only([
                'firstname', 'middlename', 'lastname',
                'birthday', 'gender', 'citizenship',
                'country', 'city', 'contact_number', 'email'
            ])
        ]);
    }

    public function step1Store(TutorVerificationRequest $request)
    {
        $this->tutorService->saveStep1(
            $request->validated(),
            $request->file('photo')
        );

        return redirect()->route('tutor.verification-2');
    }

    public function step2()
    {
        return Inertia::render('landingpage/tutor/step2', [
            'savedData' => $this->tutorService->getStep2Data(),
        ]);
    }

    public function step2Store(TutorVerificationRequest $request)
    {
        $this->tutorService->saveStep2($request->validated());

        return redirect()->route('tutor.verification-3');
    }

    public function step3()
    {
        return Inertia::render('landingpage/tutor/step3', [
            'savedData' => $this->tutorService->getStep3Data(),
        ]);
    }

    public function step3Store(TutorVerificationRequest $request)
    {
        $this->tutorService->saveStep3(
            $request->validated(),
            $request->allFiles()
        );

        return redirect()->route('tutor.landing');
    }
}