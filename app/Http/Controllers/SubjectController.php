<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SubjectService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $subjectService) {}

    public function subject()
    {
        $subjects = $this->subjectService->fetchSubjects(Auth::id());

        return Inertia::render('landingpage/tutor/mysubject', [
            'subjects' => $subjects,
        ]);
    }
    public function createsubject()
    {
        return Inertia::render('landingpage/tutor/createsubject');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'ageFrom'     => 'nullable|integer|min:1',
            'ageTo'       => 'nullable|integer|min:1',
            'price2'      => 'nullable|integer|min:10',
            'price3'      => 'nullable|integer|min:10',
            'price5'      => 'nullable|integer|min:10',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $this->subjectService->create($validated, $image);

            return redirect()->route('tutor.subject')
                ->with('success', 'Subject created successfully.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to create subject. Please try again.'])
                ->withInput();
        }
    }

        public function showmysubj($uuid)
        {
            $subject = $this->subjectService->getSubjectByUuid($uuid);

            return inertia('landingpage/tutor/subjectdetail', [
                'subject' => $subject,
            ]);
        }

    
}