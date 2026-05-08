<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth routes (handled by Laravel's built-in auth)
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Project Archives
    Route::get('/projects/archives', [ProjectController::class, 'archives'])->name('projects.archives');
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{project}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

    // Project resource routes (except archive which is handled above)
    Route::resource('projects', ProjectController::class)->except(['archives', 'restore', 'forceDelete']);

    // Task routes (nested under projects)
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', TaskController::class)->except(['index', 'show']);
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    });

    // Task index for a project (API-like but returns Blade view)
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
});

// Placeholder for email verification if needed
Route::middleware(['auth', 'verified'])->group(function () {
    // Additional verified routes can go here
});
