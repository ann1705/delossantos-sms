<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Public routes in API
Route::get('/', function () {
    return response()->json([
        'message' => 'API is running. Use /api/login, /api/register, and other /api endpoints.',
        'status' => 'ok',
    ], 200);
})->name('api.home');

Route::get('/forms', [FormController::class, 'index'])->name('forms.index');

// Token authentication
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
        'token_type' => 'Bearer',
    ]);
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'student', // Default role
    ]);

    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
        'token_type' => 'Bearer',
    ], 201);
});

// Forgot Password Routes (Public - for unauthenticated users)
Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ], [
        'email.exists' => 'No account found with this email',
    ]);

    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    Cache::put('password_reset_code:' . $request->email, $code, now()->addMinutes(30));

    return response()->json([
        'message' => 'Password reset code generated successfully.',
        'reset_code' => $code,
        'expires_in_minutes' => 30,
    ], 200);
});

// Verify Reset Code
Route::post('/verify-code', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string|size:6',
    ]);

    // Pad the code with leading zeros to match the stored format
    $paddedCode = str_pad($request->code, 6, '0', STR_PAD_LEFT);

    $storedCode = Cache::get('password_reset_code:' . $request->email);

    if (!$storedCode || $storedCode !== $paddedCode) {
        return response()->json([
            'message' => 'Invalid or expired code',
        ], 422);
    }

    // Generate a reset token
    $resetToken = Hash::make($request->email . time());

    // Store reset token with 30 minute expiry
    Cache::put('password_reset_token:' . $request->email, $resetToken, now()->addMinutes(30));

    return response()->json([
        'message' => 'Code verified successfully',
        'token' => $resetToken,
    ]);
});

// Reset Password with token
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|min:8|confirmed',
        'token' => 'required|string',
    ]);

    $storedToken = Cache::get('password_reset_token:' . $request->email);

    if (!$storedToken) {
        return response()->json([
            'message' => 'Invalid or expired token',
        ], 422);
    }

    $user = User::where('email', $request->email)->first();
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Clear the reset token and code
    Cache::forget('password_reset_token:' . $request->email);
    Cache::forget('password_reset_code:' . $request->email);

    return response()->json([
        'message' => 'Password reset successfully',
    ]);
});

// Authenticated API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    Route::get('/applications/view/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/my-application/view', [ApplicationController::class, 'viewForm'])->name('applications.view_form');
    Route::get('/my-application/pdf', [ApplicationController::class, 'downloadPdf'])->name('applications.view_form.pdf');

    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/delete', [ProfileController::class, 'destroy']);

    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::match(['post', 'put'], '/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('/forms/download/{id}', [FormController::class, 'download'])->name('forms.download');

    Route::prefix('admin')->middleware('can:admin-or-secretary')->group(function () {
        Route::get('/registry', [ApplicationController::class, 'index'])->name('admin.registry');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::get('/applications/{id}/pdf', [ApplicationController::class, 'downloadAdminPdf'])->name('admin.applications.pdf');
        Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applications.status');
        Route::delete('/applications/{id}', [ApplicationController::class, 'adminDestroy'])->name('admin.applications.destroy');
    });

    Route::delete('/user/{id}', [ApplicationController::class, 'userDestroy'])->middleware('can:admin-only');
    Route::delete('/users/{id}', [ApplicationController::class, 'userDestroy'])->middleware('can:admin-only');
    Route::post('/user/{id}/delete', [ApplicationController::class, 'userDestroy'])->middleware('can:admin-only');
    Route::post('/users/{id}/delete', [ApplicationController::class, 'userDestroy'])->middleware('can:admin-only');

    Route::prefix('admin')->middleware('can:admin-only')->group(function () {
        Route::get('/users', [ApplicationController::class, 'userIndex'])->name('admin.users');
        Route::post('/users', [ApplicationController::class, 'userStore'])->name('admin.users.store');
        Route::delete('/users/{id}', [ApplicationController::class, 'userDestroy'])->name('admin.users.destroy');

        Route::post('/forms/upload', [FormController::class, 'store'])->name('admin.forms.store');
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');
    });
});

Route::middleware('auth:sanctum')->get('/debug-user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum')->get('/debug-auth', function (Request $request) {
    return response()->json([
        'bearer' => $request->bearerToken(),
        'user' => optional($request->user())->only(['id','name','email']),
    ]);
});
