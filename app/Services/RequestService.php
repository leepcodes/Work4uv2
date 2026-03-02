<?php

namespace App\Services;

use App\Models\TutorRequest;
use App\Models\Schedule;
use Illuminate\Support\Facades\Storage;


class RequestService
{
    public function submitCustomRequest(array $data): void
    {
        $existing = TutorRequest::where('student_id', auth()->id())
            ->where('subject_id', $data['subject_id'])
            ->whereIn('status', ['pending', 'offered'])
            ->first();

        if ($existing) {
            if ($existing->status === 'pending') {
                throw new \Exception('*You have already applied for this subject.');
            }

            if ($existing->status === 'offered') {
                throw new \Exception('This subject already has an offer waiting for your response.');
            }
        }

        TutorRequest::create([
            'student_id'         => auth()->id(),
            'subject_id'         => $data['subject_id'],
            'tutor_id'           => $data['tutor_id'],
            'requestid'          => \Illuminate\Support\Str::uuid(),
            'message'            => $data['message'] ?? null,
            'custom_class_count' => $data['custom_class_count'],
            'status'             => 'pending',
        ]);
    }

   public function getTutorRequests(string $status = 'pending'): array
    {
        return TutorRequest::with(['student', 'subject'])
            ->where('tutor_id', auth()->id())
            ->whereIn('status', ['pending', 'offered']) 
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($req) => [
                'id'                 => $req->id,
                'offer_message'      => $req->offer_message,
                'message'            => $req->message,
                'custom_class_count' => $req->custom_class_count,
                'created_at'         => $req->created_at,
                'status'             => $req->status,
                'tutor_custom_price' => $req->tutor_custom_price,
                'student' => [
                    'username' => $req->student?->username,
                    'photo'    => $req->student?->photo,
                ],
                'subject' => [
                    'subject_title' => $req->subject?->subject_title,
                ],
            ])
            ->toArray();
    }

    public function getStudentRequests(): array
    {
        return TutorRequest::with(['subject', 'tutor'])
            ->where('student_id', auth()->id())
            ->where('status', 'offered')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($req) => [
                'id'                 => $req->id,
                'message'            => $req->message,
                'offer_message'      => $req->offer_message,
                'custom_class_count' => $req->custom_class_count,
                'created_at'         => $req->created_at,
                'status'             => $req->status,
                'tutor' => [
                    'username' => $req->tutor?->username,
                    'photo'    => $req->tutor?->photo
                        ? Storage::disk('work4u_storage')->url($req->tutor->photo)
                        : null,
                ],
                'subject' => [
                    'subject_title' => $req->subject?->subject_title,
                ],
            ])
            ->toArray();
    }
    public function makeOffer(array $data): void
    {
        TutorRequest::where('id', $data['request_id'])
            ->where('tutor_id', auth()->id())
            ->update([
                'status'             => 'offered',
                'tutor_custom_price' => $data['offer_price'],
                'offer_message'            => $data['offer_message'] ?? null,
            ]);
    }

    public function declineOffer(int $requestId): void
    {
        TutorRequest::where('id', $requestId)
            ->where('student_id', auth()->id())
            ->update(['status' => 'declined']);
    }

    public function acceptOffer(int $requestId): void
    {
        $request = TutorRequest::where('id', $requestId)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $request->update(['status' => 'accepted']);

        Schedule::create([
            'user_id'           => auth()->id(),
            'tutor_id'          => $request->tutor_id,
            'subject_id'        => $request->subject_id,
            'class_count'       => $request->custom_class_count,
            'available_date'    => now(),
            'tutor_custom_price' => $request->tutor_custom_price,
        ]);
    }
}