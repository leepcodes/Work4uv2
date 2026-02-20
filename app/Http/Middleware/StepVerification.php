<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StepVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }


    //IF NAG LOG OUT KASI BOBO USER :)
    public function step1()
    {
        $step = auth()->user()->verification_step;

        if ($step >= 2) return redirect()->route('tutor.verification-2');
        if ($step >= 3) return redirect()->route('tutor.verification-3');

        return Inertia::render('landingpage/tutor/step1');
    }

    public function step2()
    {
        $step = auth()->user()->verification_step;

        if ($step < 1) return redirect()->route('tutor.verification-1');

        return Inertia::render('landingpage/tutor/step2');
    }

    public function step3()
    {
        $step = auth()->user()->verification_step;

        if ($step < 2) return redirect()->route('tutor.verification-2');

        return Inertia::render('landingpage/tutor/step3');
    }
}
