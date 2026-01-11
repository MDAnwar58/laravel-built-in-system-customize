<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated, redirect them away from auth pages
        if (Auth::check()) {
            // Determine where to redirect authenticated users based on their role
            $user = Auth::user();

            if ($user->role === 'admin') {
                $redirectRoute = route('admin.dashboard');
            } elseif ($user->role === 'user') {
                $redirectRoute = route('user.dashboard');
            } else {
                $redirectRoute = route('home');
            }

            // Return redirect response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Already authenticated.'], 403);
            }

            return redirect()->to($redirectRoute);
        }

        return $next($request);
    }
}
