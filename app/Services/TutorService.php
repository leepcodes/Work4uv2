<?php

namespace App\Services;

use App\Models\User;

class TutorService
{
    public function saveStep1(array $data): void
    {
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
            'photo'             => null,
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

        if (isset($files['id_front'])) {
            $stored['id_front'] = $files['id_front']->store('tutor/documents', 'work4u_storage');
        }
        if (isset($files['id_back'])) {
            $stored['id_back'] = $files['id_back']->store('tutor/documents', 'work4u_storage');
        }
        if (isset($files['cv_resume'])) {
            $stored['cv_resume'] = $files['cv_resume']->store('tutor/documents', 'work4u_storage');
        }
        if (isset($files['certificates'])) {
            $stored['certificates'] = collect($files['certificates'])
                ->map(fn($file) => $file->store('tutor/certificates', 'work4u_storage'))
                ->toArray();
        }

        auth()->user()->update([
            'documents'         => json_encode($stored),
            'certificates'      => json_encode($stored['certificates'] ?? []),
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
}