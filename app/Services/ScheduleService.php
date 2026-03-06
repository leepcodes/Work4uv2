<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

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
                'uuid'                  => $schedule->uuid,
                'total_class_count'     => $schedule->total_class_count,
                'complete_class_count'  => $schedule->complete_class_count,
                'remaining_class_count' => $schedule->remaining_class_count,
                'available_date'        => $schedule->available_date,
                'created_at'            => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d-m-Y')
                    : null,
                'tutor' => [
                    'id'    => $schedule->tutor?->id,
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
                'uuid'                  => $schedule->uuid,
                'total_class_count'     => $schedule->total_class_count,
                'complete_class_count'  => $schedule->complete_class_count,
                'remaining_class_count' => $schedule->remaining_class_count,
                'available_date'        => $schedule->available_date,
                'created_at'            => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d-m-Y')
                    : null,
                'student' => [
                    'id'    => $schedule->user?->id,
                    'name'  => $schedule->user?->username,
                    'photo' => $schedule->user?->photo
                        ? Storage::disk('work4u_storage')->url($schedule->user->photo)
                        : null,
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

    
    public function getStudentWithSubjects(string $uuid): array
    {
        $schedule = Schedule::where('uuid', $uuid)
            ->where('tutor_id', Auth::id())
            ->firstOrFail();

        $student = User::findOrFail($schedule->user_id);

        $subjects = Schedule::where('user_id', $student->id)
            ->where('tutor_id', Auth::id())
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(function ($schedules) {
                $first = $schedules->first();
                return [
                    'id'       => $first->subject->id,
                    'title'    => $first->subject->subject_title,
                    'packages' => $schedules->map(fn ($s) => [
                        'id'                    => $s->id,
                        'uuid'                  => $s->uuid,
                        'total_class_count'     => $s->total_class_count,
                        'complete_class_count'  => $s->complete_class_count,
                        'remaining_class_count' => $s->remaining_class_count,
                        'available_date'        => $s->available_date,
                        'purchase_date'         => $s->created_at->toDateString(),
                        'tutor_custom_price'    => $s->tutor_custom_price,
                        'status'                => $s->remaining_class_count === 0 ? 'completed' : 'active',
                    ])->values(),
                ];
            })->values();

        return [
            'student' => [
                'id'    => $student->id,
                'uuid'  => $student->uuid,
                'name'  => $student->username,
                'photo' => $student->photo,
            ],
            'subjects' => $subjects,
        ];
    }

   
    public function getStudentSubjectsAndPackages(string $uuid): array
    {
        $schedule = Schedule::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $tutor = User::findOrFail($schedule->tutor_id);

        $subjects = Schedule::where('user_id', Auth::id())
            ->where('tutor_id', $tutor->id)
            ->with('subject')
            ->get()
            ->groupBy('subject_id')
            ->map(function ($schedules) {
                $first = $schedules->first();
                return [
                    'id'       => $first->subject->id,
                    'title'    => $first->subject->subject_title,
                    'packages' => $schedules->map(fn ($s) => [
                        'id'                    => $s->id,
                        'uuid'                  => $s->uuid,
                        'total_class_count'     => $s->total_class_count,
                        'complete_class_count'  => $s->complete_class_count,
                        'remaining_class_count' => $s->remaining_class_count,
                        'available_date'        => $s->available_date,
                        'purchase_date'         => $s->created_at->toDateString(),
                        'tutor_custom_price'    => $s->tutor_custom_price,
                        'status'                => $s->remaining_class_count === 0 ? 'completed' : 'active',
                    ])->values(),
                ];
            })->values();

        return [
            'tutor' => [
                'id'    => $tutor->id,
                'uuid'  => $tutor->uuid,
                'name'  => $tutor->firstname . ' ' . $tutor->lastname,
                'photo' => $tutor->photo
                    ? Storage::disk('work4u_storage')->url($tutor->photo)
                    : null,
            ],
            'subjects' => $subjects,
        ];
    }

    public function getPackageClasses(string $uuid): array
    {
        $schedule = Schedule::with(['subject', 'user', 'classes'])
            ->where('tutor_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        return [
            'student' => [
                'id'    => $schedule->user?->id,
                'name'  => $schedule->user?->username,
                'photo' => $schedule->user?->photo
                    ? Storage::disk('work4u_storage')->url($schedule->user->photo)
                    : null,
            ],
            'schedule' => [
                'id'                    => $schedule->id,
                'total_class_count'     => $schedule->total_class_count,
                'complete_class_count'  => $schedule->complete_class_count,
                'remaining_class_count' => $schedule->remaining_class_count,
                'available_date'        => $schedule->available_date,
                'purchase_date'         => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d/m/Y')
                    : null,
                'tutor_custom_price'    => $schedule->tutor_custom_price,
            ],
            'subject' => [
                'id'    => $schedule->subject?->id,
                'title' => $schedule->subject?->subject_title,
            ],
            'classes' => $schedule->classes->map(fn ($cls) => [
                'id'           => $cls->id,
                'class_number' => $cls->class_number,
                'scheduled_at' => $cls->scheduled_at,
                'status'       => $cls->status,
            ])->toArray(),
        ];
    }
}