<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // --- SCHOLARSHIP REGISTRY ---

    public function index(Request $request)
    {
        $search = $request->input('search');
        $applications = Application::with('user')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('course', 'like', "%{$search}%");
            })
            ->latest()->get();

        return view('admin.registry', compact('applications'));
    }

    public function create()
    {
        // If Student clicks "Apply Now", show Google Form style
        if (auth()->user()->role === 'student') {
            return view('applications.student_form');
        }

        // Admin version (for manual encoding)
        $users = User::where('role', 'student')->get();
        return view('applications.create', compact('users'));
    }

    public function store(Request $request)
    {
        // 1. STUDENT SUBMISSION (Google Form Logic)
        if (auth()->user()->role === 'student') {
            $request->validate([
                'course' => 'required|string',
                'gwa'    => 'required|numeric',
            ]);

            Application::create([
                'user_id'    => auth()->id(),
                'course'     => $request->course,
                'gwa'        => $request->gwa,
                'year_level' => 1, // Default starting values
                'semester'   => 1,
                'status'     => 'pending',
            ]);

            return redirect()->route('applications.success');
        }

        // 2. ADMIN SUBMISSION (Registry Logic)
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'course'     => 'required|string|max:255',
            'year_level' => 'required',
            'semester'   => 'required',
            'status'     => 'required|in:pending,approved,rejected'
        ]);

        Application::create($data);
        return redirect()->route('admin.registry')->with('success', 'Application recorded!');
    }

    public function success()
    {
        return view('applications.success');
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        // This allows updating all fields including status, course, etc.
        $application->update($request->all());

        return redirect()->route('admin.registry')->with('success', 'Record updated.');
    }

    public function destroy($id)
    {
        Application::findOrFail($id)->delete();
        return back()->with('success', 'Record deleted.');
    }

    // --- USER MANAGEMENT (Added Codes) ---

    public function userIndex(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
        ->latest()
        ->get();

        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,student',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User account created successfully.');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User account deleted.');
    }
}
