<?php

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes with direct browser paths (restore)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/forms', [FormController::class, 'index'])->name('forms.index');

// 2. AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/applications/view/{id}', [ApplicationController::class, 'show'])->name('applications.show');

    Route::get('/dashboard', [\App\Http\Controllers\StudentController::class, 'index'])->name('student.dashboard');

    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::get('/applications/apply', [ApplicationController::class, 'create'])->name('applications.create');

    Route::get('/student/dashboard', [\App\Http\Controllers\StudentController::class, 'index'])->name('student.dashboard.view');

    Route::get('/my-application/view', [ApplicationController::class, 'viewForm'])
        ->name('applications.view_form')
        ->middleware('auth');

    Route::get('/my-application/pdf', [ApplicationController::class, 'downloadPdf'])
        ->name('applications.view_form.pdf')
        ->middleware('auth');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/applications/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/success', [ApplicationController::class, 'success'])->name('applications.success');
    Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('/forms/download/{id}', [FormController::class, 'download'])->name('forms.download');

    Route::prefix('admin')->middleware(['can:admin-or-secretary'])->group(function () {
        Route::get('/registry', [ApplicationController::class, 'index'])->name('admin.registry');
        Route::get('/applications/{id}/data', [ApplicationController::class, 'getApplicationData'])->name('admin.applications.data');
        Route::get('/applications/manage', [ApplicationController::class, 'manage'])->name('admin.applications.manage');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::get('/applications/{id}/edit', [ApplicationController::class, 'edit'])->name('admin.applications.edit');
        Route::get('/applications/{id}/pdf', [ApplicationController::class, 'downloadAdminPdf'])->name('admin.applications.pdf');
        Route::put('/applications/{id}', [ApplicationController::class, 'update'])->name('admin.applications.update');
        Route::patch('/applications/{id}/status', [ApplicationController::class, 'updateStatus'])->name('admin.applications.status');
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('admin.applications.destroy');
    });

    Route::prefix('admin')->middleware(['can:admin-only'])->group(function () {
        Route::get('/users', [ApplicationController::class, 'userIndex'])->name('admin.users');
        Route::post('/users', [ApplicationController::class, 'userStore'])->name('admin.users.store');
        Route::delete('/users/{id}', [ApplicationController::class, 'userDestroy'])->name('admin.users.destroy');

        Route::post('/forms/upload', [FormController::class, 'store'])->name('admin.forms.store');
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');
    });
});

// Keep register as in original
Route::get('/register', function () {
    return view('your_custom_register_file_name');
})->name('register');

// Test email route (temporary)
Route::get('/test-email', function () {
    Mail::to('test@example.com')->send(new TestEmail());
    return 'Test email sent! Check your Mailtrap inbox.';
});

require __DIR__.'/auth.php';
