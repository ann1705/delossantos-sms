<?php

namespace App\Http\Controllers;



use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        // Conditional validation - only validate fields that are being submitted
        $rules = [
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        // Add name and email validation only if they are present in the request
        if ($request->has('name')) {
            $rules['name'] = 'required|string|max:255';
        }
        if ($request->has('email')) {
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        }

        $request->validate($rules);

        // Only update name if provided
        if ($request->has('name') && $request->filled('name')) {
            $user->name = $request->name;
        }

        // Only update email if provided
        if ($request->has('email') && $request->filled('email')) {
            $user->email = $request->email;
        }

        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->profile_photo = 'uploads/profiles/' . $filename;
        }

        $user->save();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Profile updated successfully!',
                'user' => $user,
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */ // This type-hinting helps IDEs and static analysis
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            // Fix for Line 88 (revoking tokens before deletion)
            $user->tokens()->delete();

            // Fix for Line 92 (ensuring delete() works on the model instance)
            $user->delete();

            return response()->json(['message' => 'Your account has been deleted successfully.']);
        }

        // For web: logout and invalidate session
        $user->delete(); // Delete the user for web requests too
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }
}
