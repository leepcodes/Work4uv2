<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TutorVerificationRequest;
use App\Services\TutorService;
use Inertia\Inertia;

class TutorVerificationController extends Controller
{
    public function __construct(protected TutorService $tutorService) {}

     public function step1()
    {
        $user = auth()->user();

        if ($user->verification_step >= 2) return redirect()->route('tutor.verification-2');

        return Inertia::render('landingpage/tutor/step1', [
            'user' => $user->only([
                'firstname', 'middlename', 'lastname',
                'birthday', 'gender', 'citizenship',
                'country', 'city', 'contact_number', 'email'
            ])
        ]);
    }



    public function step1Store(TutorVerificationRequest $request){
  
        $this->tutorService->saveStep1(
            $request->validated(),
            // $request->file('photo')  WALA PA BUCKET :)
        );

        return redirect()->route('tutor.verification-2');
    }

    public function step2()
    {
        $user = auth()->user();

        if ($user->verification_step < 1) return redirect()->route('tutor.verification-1');
        if ($user->verification_step >= 3) return redirect()->route('tutor.verification-3');

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
        $user = auth()->user();

        if ($user->verification_step < 2) return redirect()->route('tutor.verification-2');

        return Inertia::render('landingpage/tutor/step3', [
            'savedData' => $this->tutorService->getStep3Data(),
        ]);
    }

    public function step3Store(TutorVerificationRequest $request)
    {
        $files = [];

        if ($request->hasFile('id_front')) {
            $files['id_front'] = $request->file('id_front')->store('tutor/documents', 'public');
        }
        if ($request->hasFile('id_back')) {
            $files['id_back'] = $request->file('id_back')->store('tutor/documents', 'public');
        }
        if ($request->hasFile('cv_resume')) {
            $files['cv_resume'] = $request->file('cv_resume')->store('tutor/documents', 'public');
        }
        if ($request->hasFile('certificates')) {
            $files['certificates'] = collect($request->file('certificates'))
                ->map(fn($file) => $file->store('tutor/certificates', 'public'))
                ->toArray();
        }

        $this->tutorService->saveStep3($request->validated(), $files);

        return redirect()->route('tutor.verification-complete');
    }

}