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

    // Tasks (Global)
    Route::get('/tasks', [TaskController::class, 'globalIndex'])->name('tasks.global');
    
    // Backlog (Global)
    Route::get('/backlog', [TaskController::class, 'backlog'])->name('backlog');

    // Board (Global)
    Route::get('/board', [TaskController::class, 'board'])->name('board');

    // Team
    Route::get('/team', [DashboardController::class, 'team'])->name('team');
    Route::post('/team', [DashboardController::class, 'storeUser'])->name('team.store');
    Route::delete('/team/{user}', [DashboardController::class, 'destroyUser'])->name('team.destroy');

    // Project Archives
    Route::get('/projects/archives', [ProjectController::class, 'archives'])->name('projects.archives');
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('/projects/{project}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

    // Project resource routes
    Route::resource('projects', ProjectController::class);

    // Project member routes
    Route::post('/projects/{project}/invite', [ProjectController::class, 'invite'])->name('projects.invite');
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.remove-member');

    // Task routes (nested under projects for creation/editing)
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', TaskController::class)->except(['index', 'show']);
        Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
    });

    // Task index for a project
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])->name('tasks.index');
});

// Placeholder for email verification if needed
Route::middleware(['auth', 'verified'])->group(function () {
    // Additional verified routes can go here
});
