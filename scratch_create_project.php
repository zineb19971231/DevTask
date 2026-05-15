<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::beginTransaction();

try {
    $lead = User::where('email', 'lead@devtrack.com')->first();
    if (!$lead) {
        $lead = User::create([
            'name' => 'Lead User',
            'email' => 'lead@devtrack.com',
            'password' => bcrypt('password'),
            'role' => 'lead',
        ]);
    }

    $project = Project::create([
        'titre' => 'Alpha Project',
        'description' => 'A test project created by the AI to verify CRUD functionality.',
        'deadline' => now()->addMonths(3),
    ]);

    $project->members()->attach($lead->id, ['role' => 'lead']);

    DB::commit();
    echo "Project 'Alpha Project' created successfully for " . $lead->email . "\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
