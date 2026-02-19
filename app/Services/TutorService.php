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
        auth()->user()->update([
            'documents'         => json_encode([
                'id_front'  => $files['id_front'] ?? null,
                'id_back'   => $files['id_back'] ?? null,
                'cv_resume' => $files['cv_resume'] ?? null,
            ]),
            'certificates'      => json_encode($files['certificates'] ?? []),
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