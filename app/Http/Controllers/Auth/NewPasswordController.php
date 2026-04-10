<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Pad the code with leading zeros
        $paddedCode = str_pad($request->code, 6, '0', STR_PAD_LEFT);

        // Check if the code exists and matches and is not expired
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $paddedCode)
            ->where('created_at', '>', now()->subMinutes(60))
            ->first();

        if (!$resetToken) {
            return back()
                ->with('code_verified', true)
                ->with('code_sent', true)
                ->with('reset_email', $request->email)
                ->with('reset_code', $request->code)
                ->withInput(['email' => $request->email, 'code' => $request->code])
                ->withErrors(['code' => 'Invalid or expired reset code.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ]);

            // Delete the used token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            event(new PasswordReset($user));

            return redirect()->route('login')->with('status', '✅ Your password has been reset successfully! You can now log in with your new password.');
        }

        return back()
            ->with('code_verified', true)
            ->with('code_sent', true)
            ->with('reset_email', $request->email)
            ->with('reset_code', $request->code)
            ->withInput(['email' => $request->email, 'code' => $request->code])
            ->withErrors(['email' => 'User not found.']);
    }


}
