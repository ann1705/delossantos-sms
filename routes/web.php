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

    // This is the route for the Apply button
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');


    // Student Specific Dashboard
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scholarship Applications
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');

    // Downloads
    Route::get('/forms/download/{id}', [FormController::class, 'download'])->name('forms.download');

    // 3. ADMIN ROUTES
    Route::prefix('admin')->group(function () {
        // Registry Management
        Route::get('/registry', [ApplicationController::class, 'index'])->name('admin.registry');
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

        // User Management
        Route::get('/users', [ApplicationController::class, 'userIndex'])->name('admin.users');
        Route::post('/users/store', [ApplicationController::class, 'userStore'])->name('admin.users.store');
        Route::delete('/users/{id}', [ApplicationController::class, 'userDestroy'])->name('admin.users.destroy');

        // Form Management
        Route::post('/forms/upload', [FormController::class, 'store'])->name('admin.forms.store');
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');

        Route::get('/scholarship/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/scholarship/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/scholarship/success', [ApplicationController::class, 'success'])->name('applications.success');

    // Admin Registry Routes
    Route::get('/admin/registry', [ApplicationController::class, 'index'])->name('admin.registry');
    Route::put('/admin/registry/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/admin/registry/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    // Add this OUTSIDE the auth middleware group
Route::get('/register', function () {
    return view('your_custom_register_file_name');
})->name('register');
    });
});

// CRITICAL: This file contains the /login and /register routes
require __DIR__.'/auth.php';
