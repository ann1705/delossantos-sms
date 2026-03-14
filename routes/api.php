<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

// These routes will be prefixed with /api/
Route::prefix('admin')->group(function () {

    // User Management API
    Route::get('/users', [ApplicationController::class, 'userIndex']);
    Route::post('/users', [ApplicationController::class, 'userStore']);

    // Application Management API
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::get('/registry', [ApplicationController::class, 'index']);
});
