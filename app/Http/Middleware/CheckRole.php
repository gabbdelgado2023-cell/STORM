<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== $role) {
            // Redirect based on user's actual role
            return match($request->user()->role) {
                'student' => redirect()->route('student.dashboard'),
                'officer' => redirect()->route('officer.dashboard'),
                'dean' => redirect()->route('dean.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                default => redirect()->route('login')
            };
        }

        return $next($request);
    }
}