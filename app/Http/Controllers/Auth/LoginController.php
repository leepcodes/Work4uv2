<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(protected LoginService $loginService) {}

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!$this->loginService->authenticate($credentials, $request)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Redirect based on role
        $role = Auth::user()->role;

        return match($role) {
            'student' => redirect()->route('student.landing'),
            'tutor'   => redirect()->route('tutor.dashboard'),
            default   => redirect('/'),
        };
    }
}