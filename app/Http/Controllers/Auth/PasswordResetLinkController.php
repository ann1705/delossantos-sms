<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the code in password_reset_tokens table
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $code, 'created_at' => now()]
        );

        // Send notification with code
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $user->notify(new \App\Notifications\ResetPasswordNotification($code));
        }

        return back()
            ->with('status', 'We have emailed your password reset code!')
            ->with('code_sent', true)
            ->with('reset_email', $request->email)
            ->withInput(['email' => $request->email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        // Pad the code with leading zeros
        $paddedCode = str_pad($request->code, 6, '0', STR_PAD_LEFT);

        // Check if the code matches and is not expired
        $resetToken = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $paddedCode)
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if ($resetToken) {
            // Code is valid, set verified
            return back()
                ->with('code_sent', true)
                ->with('code_verified', true)
                ->with('reset_email', $request->email)
                ->with('reset_code', $request->code)
                ->with('status', 'Code verified! Please set your new password.')
                ->withInput(['email' => $request->email, 'code' => $request->code]);
        } else {
            return back()
                ->with('code_sent', true)
                ->with('reset_email', $request->email)
                ->withInput(['email' => $request->email])
                ->withErrors(['code' => 'Invalid or expired code.']);
        }
    }
}
