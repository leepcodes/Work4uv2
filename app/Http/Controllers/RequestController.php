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

    public function requests(Request $request)
    {
        $status = $request->query('status', 'offered');
        $requests = $this->requestService->getStudentRequests($status);

        return Inertia::render('landingpage/student/request', [
            'requests' => $requests,
            'role'     => 'student',
        ]);
    }

    public function tutorRequests(Request $request)
    {
        $status = $request->query('status', 'pending');
        $statuses = $status === 'pending' ? ['pending', 'offered'] : [$status];

        $requests = $this->requestService->getTutorRequests($statuses);

        return Inertia::render('landingpage/tutor/request', [
            'requests' => $requests,
            'role'     => 'tutor',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id'         => 'required|integer',
            'tutor_id'           => 'required|integer',
            'message'            => 'nullable|string',
            'custom_class_count' => 'required|integer|min:1',
        ]);

        try {
            $this->requestService->submitCustomRequest($validated);
            return back()->with('success', 'Request submitted successfully!');
        } catch (\Exception $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'custom_request' => $e->getMessage()
            ]);
        }
    }

    public function makeOffer(Request $request)
    {
        $validated = $request->validate([
            'request_id'    => 'required|integer',
            'offer_price'   => 'required|numeric|min:1',
            'offer_message' => 'nullable|string',
        ]);

        $this->requestService->makeOffer($validated);

        return back()->with('success', 'Offer sent successfully!');
    }

    public function declineOffer(Request $request)
    {
        $this->requestService->declineOffer($request->request_id);
        return back()->with('success', 'Offer declined.');
    }

    public function acceptOffer(Request $request)
    {
        $this->requestService->acceptOffer($request->request_id);
        return back()->with('success', 'Offer accepted.');
    }
}