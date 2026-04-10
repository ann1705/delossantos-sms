<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login'); // This fixes the "Method does not exist" error
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (in_array($request->user()->role, ['admin', 'secretary'])) {
            return redirect()->intended(route('admin.registry'));
        }

        return redirect()->intended(route('student.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function authenticated(Request $request, $user)
{
    if (in_array($user->role, ['admin', 'secretary'])) {
        return redirect()->route('admin.registry'); // Or your admin dashboard route
    }

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    }

    return redirect('/home');
}



}
