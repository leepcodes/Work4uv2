<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class NavbarService
{
    public function getNavbarData(): array
    {
        $user = auth()->user();

        return [
            'photoUrl' => $user->photo
                ? Storage::disk('work4u_storage')->url($user->photo)
                : null,
        ];
    }
}