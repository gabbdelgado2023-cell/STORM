<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use App\Models\AuditLog;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // ✅ Validate request directly
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // ✅ Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard'); // change to your route
        }

        // ✅ If failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Log the action
        AuditLog::create([
            'user_id'   => auth()->id(),
            'action'    => 'User logged out',
            'ip_address'=> $request->ip(),
        ]);

        // Proper logout
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Role-based redirection after login.
     */
    protected function redirectToRole(User $user): string
    {
        return match($user->role) {
            'student' => '/student/dashboard',
            'officer' => '/officer/dashboard',
            'dean'    => '/dean/dashboard',
            'admin'   => '/admin/dashboard',
            default   => '/dashboard',
        };
    }

}
