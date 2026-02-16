<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthService
{
    
    public function logout(Request $request): void
    {
       
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $this->logLogout($user);
    }
    
    /**
     * Log logout activity
     */
    protected function logLogout($user): void
    {
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
            ]);
        }
    }
}