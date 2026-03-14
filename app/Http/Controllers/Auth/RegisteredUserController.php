<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException; // Added for specific error handling
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student', // Automatically assigns student role
            ]);

            event(new Registered($user));

            Auth::login($user);

            // Redirects to student.dashboard with the success notification we built
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome, ' . $user->name . '! Your scholarship account has been created successfully.');

        } catch (ValidationException $e) {
            // This allows Laravel's default validation errors to show up in our SweetAlert Warning popup
            throw $e;
        } catch (\Exception $e) {
            // General catch for database or server issues
            return back()->with('error', 'Something went wrong during registration. Please try again later.');
        }
    }
}
