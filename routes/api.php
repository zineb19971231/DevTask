<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // API endpoint for project tasks (US13)
    Route::get('/projects/{project}/tasks', [TaskController::class, 'apiIndex'])->name('api.projects.tasks');
});
