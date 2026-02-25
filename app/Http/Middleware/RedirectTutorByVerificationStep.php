<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectTutorByVerificationStep
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only apply to authenticated tutors
        if (!$user || $user->role !== 'tutor') {
            return $next($request);
        }

        $step = $user->verification_step ?? 0;

        // If already on the correct route, don't redirect (prevent redirect loop)
        $currentRoute = $request->route()->getName();

        // Tutor is fully verified — always go to landing
        if ($step >= 3) {
            if ($currentRoute === 'tutor.landing') {
                return $next($request);
            }
            return redirect()->route('tutor.landing');
        }

        // Map incomplete steps to their route
        $stepRoutes = [
            0 => 'tutor.verification-1',
            1 => 'tutor.verification-2',
            2 => 'tutor.verification-3',
        ];

        $targetRoute = $stepRoutes[$step] ?? 'tutor.verification-1';

        // Allow access to the target step route and store routes
        $allowedRoutes = [
            'tutor.verification-1',
            'tutor.verification-1.store',
            'tutor.verification-2',
            'tutor.verification-2.store',
            'tutor.verification-3',
            'tutor.verification-3.store',
        ];

        // Only redirect if not already on an allowed verification route
        if (!in_array($currentRoute, $allowedRoutes)) {
            return redirect()->route($targetRoute);
        }

        return $next($request);
    }
}