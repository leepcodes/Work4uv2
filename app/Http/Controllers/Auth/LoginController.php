<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function __construct(protected LoginService $loginService) {}

    public function index()
    {
        return Inertia::render('landingpage/tutor/tutorlanding');
    }


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

        return redirect()->route($this->loginService->getRedirectRoute());
    }
}