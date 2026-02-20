<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function authenticate(array $credentials, Request $request): bool
    {
        if (!Auth::attempt($credentials)) {
            return false;
        }

        $request->session()->regenerate();
        return true;
    }

    public function getRedirectRoute(): string
    {
        return match(Auth::user()->role) {
            'student' => 'student.landing',
            'tutor'   => 'tutor.verification-1',
            default   => '/',
        };
    }
}