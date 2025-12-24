<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, continue with the request (let them see the normal home page)
        if (!Auth::check()) {
            return $next($request);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Redirect based on user role
        if ($user->role === 'admin') {
            // Redirect admin users to admin dashboard
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'user') {
            // User role can stay on the normal home page
            return $next($request);
        }

        // For any other roles or edge cases, continue with the request
        return $next($request);
    }
}
