<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisterController extends Controller
{
    protected $registrationService;
    
    public function index()
    {
        return Inertia::render('auth/register');
    }

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }
    
    public function create()
    {
        return Inertia::render('auth/register');
    }
    
    public function store(Request $request)
{
    $validated = $request->validate([
        'username' => [
            'required',
            'string',
            'max:255',
            'unique:users',
            'alpha_dash',
        ],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
        ],
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
        ],
        'user_type' => [
            'required',
            'in:student,tutor',
        ],
    ]);
    
    try {
        // Create user (don't log in)
        $user = $this->registrationService->register($validated);
        
        // Redirect to login page with success message
        return redirect()->route('login')
            ->with('success', 'Account created successfully! Please login.');
        
    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Registration failed. Please try again.'])
            ->withInput($request->except('password', 'password_confirmation'));
    }
}
}