<?php

namespace App\Http\Controllers;

use App\Services\RequestService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RequestController extends Controller
{
    public function __construct(
        protected RequestService $requestService,
    ) {}

    public function requests()
    {
        return Inertia::render('landingpage/student/request');
    }
    public function tutorrequests()
    {
        return Inertia::render('landingpage/tutor/request');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer',
            'tutor_id'           => 'required|string',
            'message'            => 'nullable|string',
            'custom_class_count' => 'required|integer|min:1',
        ]);

        $this->requestService->submitCustomRequest($validated);

        return back()->with('success', 'Request submitted successfully!');
    }
}