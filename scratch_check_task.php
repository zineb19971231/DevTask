<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Task;
use App\Models\Project;
use App\Models\User;

$lastTask = Task::with(['project', 'user'])->latest()->first();

if ($lastTask) {
    echo "Last Task Found:\n";
    echo "ID: " . $lastTask->id . "\n";
    echo "Title: " . $lastTask->titre . "\n";
    echo "Project: " . ($lastTask->project->titre ?? 'NULL') . " (ID: " . $lastTask->projet_id . ")\n";
    echo "Assigned To: " . ($lastTask->user->name ?? 'NULL') . " (ID: " . $lastTask->user_id . ")\n";
    
    $currentUser = User::first(); // Just for testing context
    echo "\nMembership Check for first user (" . $currentUser->name . "):\n";
    $isMember = $lastTask->project->members()->where('user_id', $currentUser->id)->exists();
    echo "Is Member: " . ($isMember ? 'YES' : 'NO') . "\n";
} else {
    echo "No tasks found in DB.\n";
}
