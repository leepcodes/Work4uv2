<?php

namespace App\Services;

use App\Models\TutorRequest;

class RequestService
{
    public function submitCustomRequest(array $data): void
    {
        TutorRequest::create([
            'student_id'         => auth()->id(),
            'subject_id'         => $data['subject_id'],
            'tutor_id'           => $data['tutor_id'],
            'message'            => $data['message'] ?? null,
            'custom_class_count' => $data['custom_class_count'],
            'status'             => 'pending',
        ]);
    }
}