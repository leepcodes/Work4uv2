<?php

namespace App\Services;
use App\Models\User;
use App\Models\Calendar;
use App\Models\Classes;
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
                    'packages' => $schedules
                        ->sortBy('created_at')
                        ->values()
                        ->map(function ($s, $idx) {
                            return [
                                'id'                    => $s->id,
                                'uuid'                  => $s->uuid,
                                'package_number'        => $idx + 1,
                                'total_class_count'     => $s->total_class_count,
                                'complete_class_count'  => $s->complete_class_count,
                                'remaining_class_count' => $s->remaining_class_count,
                                'available_date'        => $s->available_date,
                                'purchase_date'         => $s->created_at->toDateString(),
                                'tutor_custom_price'    => $s->tutor_custom_price,
                                'status'                => $s->remaining_class_count === 0 ? 'completed' : 'active',
                            ];
                        })
                        ->sortByDesc('package_number')
                        ->values(),
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

    // TUTOR: package classes
    public function getPackageClasses(string $uuid): array
{
    $schedule = Schedule::with(['subject', 'user'])
        ->where('tutor_id', auth()->id())
        ->where('uuid', $uuid)
        ->firstOrFail();

    $classes = \App\Models\Classes::where('schedule_id', $schedule->id)
        ->orderBy('class_number')
        ->get();

    $subjectTitle = $schedule->subject?->subject_title;

    return [
        'student' => [
            'id'    => $schedule->user?->id,
            'uuid'  => $schedule->user?->uuid,
            'name'  => $schedule->user?->username,
            'photo' => $schedule->user?->photo
                ? Storage::disk('work4u_storage')->url($schedule->user->photo)
                : null,
        ],
        'schedule' => [
            'id'                    => $schedule->id,
            'uuid'                  => $schedule->uuid,
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
            'title' => $subjectTitle,
        ],
        'classes' => $classes->map(fn ($cls) => [
            'id'     => $cls->id,
            'uuid'   => $cls->uuid,  
            'label'  => "{$subjectTitle} - Class {$cls->class_number}",
            'date'   => $cls->scheduled_at
                ? \Carbon\Carbon::parse($cls->scheduled_at)->format('d/m/Y H:i')
                : null,
            'status' => $cls->status,
        ])->toArray(),
    ];
}

    // STUDENT: package classes
    public function getStudentPackageClasses(string $uuid): array
    {
        $schedule = Schedule::with(['subject', 'tutor', 'classes'])
            ->where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        return [
            'tutor' => [
                'id'    => $schedule->tutor?->id,
                'uuid'  => $schedule->tutor?->uuid,
                'name'  => $schedule->tutor?->firstname . ' ' . $schedule->tutor?->lastname,
                'photo' => $schedule->tutor?->photo
                    ? Storage::disk('work4u_storage')->url($schedule->tutor->photo)
                    : null,
            ],
            'schedule' => [
                'id'                    => $schedule->id,
                'uuid'                  => $schedule->uuid,
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
            'classes' => $classes->map(fn ($cls) => [
                'id'     => $cls->id,
                'uuid'   => $cls->uuid,  // ← add this
                'label'  => "{$subjectTitle} - Class {$cls->class_number}",
                'date'   => $cls->scheduled_at
                    ? \Carbon\Carbon::parse($cls->scheduled_at)->format('d/m/Y H:i')
                    : null,
                'status' => $cls->status,
            ])->toArray(),
        ];
    }

    // TUTOR: single class details
    public function getTutorClassDetails(string $uuid, string $classUuid): array
        {
        $schedule  = Schedule::with(['subject', 'user', 'classes'])
            ->where('tutor_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        $classItem = $schedule->classes->firstWhere('uuid', $classUuid);
        // dd($classUuid, $schedule->classes->pluck('uuid'));

    return [
        'person' => [
            'id'    => $schedule->user?->id,
            'uuid'  => $schedule->user?->uuid,
            'name'  => $schedule->user?->username,
            'photo' => $schedule->user?->photo
                ? Storage::disk('work4u_storage')->url($schedule->user->photo)
                : null,
        ],
        'subject' => [
            'id'    => $schedule->subject?->id,
            'title' => $schedule->subject?->subject_title,
        ],
        'package' => [
            'id'                 => $schedule->id,
            'uuid'               => $schedule->uuid,
            'purchase_date'      => $schedule->created_at
                ? \Carbon\Carbon::parse($schedule->created_at)->format('d/m/Y')
                : null,
            'tutor_custom_price' => $schedule->tutor_custom_price,
        ],
        'classItem' => $classItem ? [
            'id'           => $classItem->id,
            'uuid'         => $classItem->uuid,
            'class_number' => $classItem->class_number,
            'scheduled_at' => $classItem->scheduled_at,
            'schedule'     => $classItem->schedule,
            'status'       => $classItem->status,
            'video_link'   => $classItem->video_link ?? null,
            'notes'        => $classItem->notes ?? null,
            'documents'    => $classItem->documents ?? null,
        ] : null,
    ];
}

    // STUDENT: single class details
    public function getStudentClassDetails(string $uuid, int $classId): array
    {
        $schedule = Schedule::with(['subject', 'tutor', 'classes'])
            ->where('user_id', auth()->id())
            ->where('uuid', $uuid)
            ->firstOrFail();

        $classItem = $schedule->classes->firstWhere('id', $classId);

        return [
            'person' => [
                'id'    => $schedule->tutor?->id,
                'uuid'  => $schedule->tutor?->uuid,
                'name'  => $schedule->tutor?->firstname . ' ' . $schedule->tutor?->lastname,
                'photo' => $schedule->tutor?->photo
                    ? Storage::disk('work4u_storage')->url($schedule->tutor->photo)
                    : null,
            ],
            'subject' => [
                'id'    => $schedule->subject?->id,
                'title' => $schedule->subject?->subject_title,
            ],
            'package' => [
                'id'                 => $schedule->id,
                'uuid'               => $schedule->uuid,
                'purchase_date'      => $schedule->created_at
                    ? \Carbon\Carbon::parse($schedule->created_at)->format('d/m/Y')
                    : null,
                'tutor_custom_price' => $schedule->tutor_custom_price,
            ],
            'classItem' => $classItem ? [
                'id'           => $classItem->id,
                'class_number' => $classItem->class_number,
                'scheduled_at' => $classItem->scheduled_at,
                'status'       => $classItem->status,
                'video_link'   => $classItem->video_link ?? null,
                'notes'        => $classItem->notes ?? null,
            ] : null,
        ];
    }


    public function getTutorCalendarData(): array
    {
        $timeSlots = Calendar::where('tutor_id', auth()->id())->where('type', 'time_slot')->get();
        $daysOff   = Calendar::where('tutor_id', auth()->id())->where('type', 'day_off')->get();

        return [
            'timeSlots' => $timeSlots->toArray(),
            'daysOff'   => $daysOff->toArray(),
        ];
    }

    public function getStudentCalendarData(string $uuid): array
    {
        $schedule  = Schedule::where('uuid', $uuid)->where('user_id', auth()->id())->firstOrFail();
        $timeSlots = Calendar::where('tutor_id', $schedule->tutor_id)->where('type', 'time_slot')->get();
        $daysOff   = Calendar::where('tutor_id', $schedule->tutor_id)->where('type', 'day_off')->get();

        return [
            'timeSlots' => $timeSlots->toArray(),
            'daysOff'   => $daysOff->toArray(),
        ];
    }

public function updateClass(array $data, array $files, string $uuid): void
{
    $class = \App\Models\Classes::where('uuid', $uuid)->firstOrFail();

    $updateData = [];

    
    if (!empty($data['schedule'])) {
        $updateData['schedule']     = $data['schedule'];
        $updateData['scheduled_at'] = $data['schedule']
            . ' '
            . (!empty($data['scheduled_time']) ? $data['scheduled_time'] : '00:00:00');
        $updateData['status'] = 'upcoming';
    }

    
    if (array_key_exists('video_link', $data)) {
        $updateData['video_link'] = $data['video_link'];
    }

    
    if (!empty($files['notes'])) {
        $existingNotes = [];
        if ($class->notes) {
            $existingNotes = json_decode($class->notes, true) ?? [];
        }
        foreach ($files['notes'] as $file) {
            $existingNotes[] = $file->store('classes/notes', 'work4u_storage');
        }
        $updateData['notes'] = json_encode($existingNotes);
    }

    
    if (!empty($files['documents'])) {
        $existing = [];
        if ($class->documents) {
            $existing = json_decode($class->documents, true) ?? [];
        }
        foreach ($files['documents'] as $file) {
            $existing[] = $file->store('classes/documents', 'work4u_storage');
        }
        $updateData['documents'] = json_encode($existing);
    }

    $class->update($updateData);
}



}