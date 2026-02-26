<?php

namespace App\Services;

use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubjectService
{
    public function create(array $data, $image = null): Subject
    {
        $imageData = null;
        if ($image) {
            $imageData = Storage::disk('work4u_storage')
                ->putFile('subjects', $image);
        }

        $subject = Subject::create([
            'uuid'         => \Illuminate\Support\Str::uuid(),
            'tutor_id'      => Auth::id(),
            'subject_title' => $data['subject'],
            'description'   => $data['description'] ?? null,
            'suitable_age'  => isset($data['ageFrom'], $data['ageTo'])
                                ? $data['ageFrom'] . '-' . $data['ageTo']
                                : null,
            'two_class'     => $data['price2'] ?? null,
            'three_class'   => $data['price3'] ?? null,
            'five_class'    => $data['price5'] ?? null,
            'image_file'    => $imageData,
        ]);

        Log::info('Subject created', ['subject_id' => $subject->id, 'tutor_id' => Auth::id()]);

        return $subject;
    }

    public function getImageUrl(string $path): string
    {
        return Storage::disk('work4u_storage')->url($path);
    }


    public function fetchSubjects(int $tutorId): \Illuminate\Support\Collection
    {
        return Subject::where('tutor_id', $tutorId)
            ->latest()
            ->get()
            ->map(function ($subject) {
                return [
                    'id'       => $subject->id,
                    'uuid'     => $subject->uuid,
                    'title'    => $subject->subject_title,
                    'status'   => $subject->status ?? null,
                    'image'    => $subject->image_file
                                    ? $this->getImageUrl($subject->image_file)
                                    : null,
                    'students' => $subject->students_count ?? 0,
                    'earnings' => $subject->earnings ?? '0 USD',
                    'packages' => [
                        ['label' => '2 Class', 'price' => 'USD ' . $subject->two_class,   'sub' => round($subject->two_class   / 2, 2) . '/class', 'color' => 'bg-teal-400'],
                        ['label' => '3 Class', 'price' => 'USD ' . $subject->three_class, 'sub' => round($subject->three_class / 3, 2) . '/class', 'color' => 'bg-purple-400'],
                        ['label' => '5 Class', 'price' => 'USD ' . $subject->five_class,  'sub' => round($subject->five_class  / 5, 2) . '/class', 'color' => 'bg-violet-500'],
                    ],
                ];
            });
        }

    public function getSubjectByUuid(string $uuid): array
    {
        $subject = Subject::with('tutor')->where('uuid', $uuid)->firstOrFail();

        return [
            'id'          => $subject->id,
            'uuid'        => $subject->uuid,
            'title'       => $subject->subject_title,
            'description' => $subject->description,
            'image'       => $subject->image_file ? $this->getImageUrl($subject->image_file) : null,
            'suitable_age'=> $subject->suitable_age,
            'two_class'   => $subject->two_class,
            'three_class' => $subject->three_class,
            'five_class'  => $subject->five_class,
            'students'    => $subject->students_count ?? 0,
            'tutor' => [
                'uuid'      => $subject->tutor->uuid,
                'firstname' => $subject->tutor->firstname,
                'lastname'  => $subject->tutor->lastname,
                'photo'     => $subject->tutor->photo  
                                ? Storage::disk('work4u_storage')->url($subject->tutor->photo)
                                : null,
            ],
        ];
    }
}