<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has the required role
        if (Auth::user()->role !== $role) {
            // Redirect to appropriate dashboard based on user's actual role
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard
     */
    private function redirectToDashboard(string $userRole)
    {
        return match($userRole) {
            'student' => redirect()->route('student.dashboard')->with('error', 'Access denied. You don\'t have permission to access that area.'),
            'officer' => redirect()->route('officer.dashboard')->with('error', 'Access denied. You don\'t have permission to access that area.'),
            'dean' => redirect()->route('dean.dashboard')->with('error', 'Access denied. You don\'t have permission to access that area.'),
            'admin' => redirect()->route('admin.dashboard')->with('error', 'Access denied. You don\'t have permission to access that area.'),
            default => redirect()->route('login')->with('error', 'Access denied. Please contact administrator.')
        };
    }
}