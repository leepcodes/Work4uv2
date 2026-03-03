<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;

class ScheduleService
{
    public function getTutorList(): array
    {
        return Schedule::with(['subject', 'tutor'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($schedule) => [
                'id'                    => $schedule->id,
                'total_class_count'     => $schedule->total_class_count,
                'complete_class_count'  => $schedule->complete_class_count,
                'remaining_class_count' => $schedule->remaining_class_count,
                'available_date'        => $schedule->available_date,
                'created_at'            => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d-m-Y')
                    : null,
                'tutor' => [
                    'name'  => $schedule->tutor?->firstname . ' ' . $schedule->tutor?->lastname,
                    'photo' => $schedule->tutor?->photo
                        ? Storage::disk('work4u_storage')->url($schedule->tutor->photo)
                        : null,
                ],
                'subject' => [
                    'title' => $schedule->subject?->subject_title,
                ],
            ])
            ->toArray();
    }


    public function getStudentList(): array
    {
        return Schedule::with(['subject', 'user'])
            ->where('tutor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($schedule) => [
                'id'                    => $schedule->id,
                'total_class_count'     => $schedule->total_class_count,
                'complete_class_count'  => $schedule->complete_class_count,
                'remaining_class_count' => $schedule->remaining_class_count,
                'available_date'        => $schedule->available_date,
                'created_at'            => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d-m-Y')
                    : null,
                'student' => [
                    'name'  => $schedule->user?->username,
                    'photo' => $schedule->user?->photo
                        ? Storage::disk('work4u_storage')->url($schedule->user->photo)
                         : null, // WAALANG PHOTO STUDENT PERO BAKA LANG 
                ],
                'subject' => [
                    'title' => $schedule->subject?->subject_title,
                ],
            ])
            ->toArray();
    }

    public function enroll(array $data): void
    {
        Schedule::create([
            'user_id'               => auth()->id(),
            'tutor_id'              => $data['tutor_id'],
            'subject_id'            => $data['subject_id'],
            'total_class_count'     => $data['total_class_count'],
            'complete_class_count'  => 0,
            'remaining_class_count' => $data['total_class_count'],
            'available_date'        => now(),
            'tutor_custom_price'    => $data['tutor_custom_price'],
        ]);
    }
}