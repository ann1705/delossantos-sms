<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/forms', [FormController::class, 'index'])->name('forms.index');

// 2. AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // General Dashboard (Default for Breeze)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::get('/applications/view/{id}', [ApplicationController::class, 'show'])->name('applications.show');

    // Your other student routes...
    Route::get('/dashboard', [ApplicationController::class, 'studentDashboard'])->name('student.dashboard');

    // This is the route for the Apply button
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::get('/applications/apply', [ApplicationController::class, 'create'])->name('applications.create');


    // Student Specific Dashboard
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');


    // Student View Route
    Route::get('/my-application/view', [App\Http\Controllers\ApplicationController::class, 'viewForm'])
    ->name('applications.view_form')
    ->middleware('auth');

    // Student PDF download (application form)
    Route::get('/my-application/pdf', [App\Http\Controllers\ApplicationController::class, 'downloadPdf'])
    ->name('applications.view_form.pdf')
    ->middleware('auth');


    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scholarship Applications
    Route::get('/applications/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/success', [ApplicationController::class, 'success'])->name('applications.success');
    Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    // Downloads
    Route::get('/forms/download/{id}', [FormController::class, 'download'])->name('forms.download');

    // 3. ADMIN ROUTES
    Route::prefix('admin')->middleware(['auth', 'can:admin-only'])->group(function () {
        // Registry Management
        Route::get('/registry', [ApplicationController::class, 'index'])->name('admin.registry');

        // Application management
        Route::get('/applications/manage', [ApplicationController::class, 'manage'])->name('admin.applications.manage');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::get('/applications/{id}/edit', [ApplicationController::class, 'edit'])->name('admin.applications.edit');
        Route::get('/applications/{id}/pdf', [ApplicationController::class, 'downloadAdminPdf'])->name('admin.applications.pdf');
        Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('admin.applications.update');
        Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applications.status');
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('admin.applications.destroy');

        // User Management
        Route::get('/users', [ApplicationController::class, 'userIndex'])->name('admin.users');
        Route::post('/users', [ApplicationController::class, 'userStore'])->name('admin.users.store');
        Route::delete('/users/{id}', [ApplicationController::class, 'userDestroy'])->name('admin.users.destroy');

        // Form Management
        Route::post('/forms/upload', [FormController::class, 'store'])->name('admin.forms.store');
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');
    });

    // Add this OUTSIDE the auth middleware group
    Route::get('/register', function () {
        return view('your_custom_register_file_name');
    })->name('register');
});

// CRITICAL: This file contains the /login and /register routes
require __DIR__.'/auth.php';
