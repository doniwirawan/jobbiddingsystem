<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('/login'); // Redirect to login if not authenticated
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the user has the required role
        if (!$user->hasRole($role)) {
            // If the user does not have the required role, return a 403 response or redirect
            abort(403, 'Unauthorized action.');
        }

        // If the user has the role, allow the request to proceed
        return $next($request);
    }
}
