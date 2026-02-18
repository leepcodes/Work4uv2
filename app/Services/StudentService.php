<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class StudentService
{
    public function getTutors(): Collection
    {
        return User::where('role', 'tutor')
            ->select('id', 'firstname', 'lastname') ->get();
            
    }
}