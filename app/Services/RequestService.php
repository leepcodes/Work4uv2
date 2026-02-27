<?php

namespace App\Services;

use App\Models\TutorRequest;
use Illuminate\Support\Facades\Storage;


class RequestService
{
    public function submitCustomRequest(array $data): void
    {
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
            ->whereIn('status', ['pending', 'offered']) // ✅ show both
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($req) => [
                'id'                 => $req->id,
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
                'message'            => $data['offer_message'] ?? null,
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
        TutorRequest::where('id', $requestId)
            ->where('student_id', auth()->id())
            ->update(['status' => 'accepted']);
    }
}