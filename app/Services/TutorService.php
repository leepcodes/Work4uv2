<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TutorService
{
    public function saveStep1(array $data): void
    {
        $photoPath = null;

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $photoPath = Storage::disk('work4u_storage')->putFile('tutor/profile', $data['photo']);
        }

        auth()->user()->update([
            'firstname'         => $data['firstname'],
            'middlename'        => $data['middlename'] ?? null,
            'lastname'          => $data['lastname'],
            'birthday'          => $data['dob'],
            'gender'            => $data['gender'],
            'citizenship'       => $data['citizenship'],
            'country'           => $data['country'],
            'city'              => $data['city'],
            'contact_number'    => $data['contact'],
            'photo'             => $photoPath,
            'verification_step' => 1,
        ]);
    }

    public function saveStep2(array $data): void
    {
        auth()->user()->update([
            'languages'         => json_encode($data['languages']),
            'service'           => json_encode($data['services']),
            'subjects'          => $data['subjects'] ?? null,
            'verification_step' => 2,
        ]);
    }

    public function getStep2Data(): array
    {
        $user = auth()->user();

        return [
            'languages' => json_decode($user->languages ?? '[]', true),
            'services'  => json_decode($user->service ?? '[]', true),
            'subjects'  => $user->subjects ?? '',
        ];
    }
    



    public function saveStep3(array $data, array $files): void
    {
        $stored = [];

        
        if (!empty($files['id_front']) && $files['id_front'] instanceof \Illuminate\Http\UploadedFile && $files['id_front']->isValid()) {
            $stored['id_front'] = $files['id_front']->store('tutor/documents', 'work4u_storage');
        }

        if (!empty($files['id_back']) && $files['id_back'] instanceof \Illuminate\Http\UploadedFile && $files['id_back']->isValid()) {
            $stored['id_back'] = $files['id_back']->store('tutor/documents', 'work4u_storage');
        }

        if (!empty($files['cv_resume']) && $files['cv_resume'] instanceof \Illuminate\Http\UploadedFile && $files['cv_resume']->isValid()) {
            $stored['cv_resume'] = $files['cv_resume']->store('tutor/documents', 'work4u_storage');
        }

        if (!empty($files['certificates']) && is_array($files['certificates'])) {
            $stored['certificates'] = collect($files['certificates'])
                ->filter(fn($file) =>
                    $file instanceof \Illuminate\Http\UploadedFile && $file->isValid()
                )
                ->map(fn($file) => $file->store('tutor/certificates', 'work4u_storage'))
                ->values()
                ->toArray();
        }

    
        $user = auth()->user();
        $existingDocuments = json_decode($user->documents ?? '[]', true) ?? [];

        $mergedDocuments = array_merge($existingDocuments, $stored);

        $user->update([
            'documents'         => json_encode($mergedDocuments),
            'certificates'      => json_encode($stored['certificates'] ?? json_decode($user->certificates ?? '[]', true) ?? []),
            'description'       => $data['description'],
            'verification_step' => 3,
        ]);
    }

    public function getStep3Data(): array
    {
        $user = auth()->user();

        return [
            'description' => $user->description ?? '',
        ];
    }

   
    
    // public function getTutorSubjects(int $tutorId): array
    // {
    //     $user = User::with('subjects')->findOrFail($tutorId);
    //     return $user->subjects->toArray();
    // }

   
}