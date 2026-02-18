<?php              

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authorization
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this page.');
        }

        $user = Auth::user();

        if (empty($roles)) {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access. You do not have permission to view this page.');
        }

        return $next($request);
    }
}