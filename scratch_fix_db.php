<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasColumn('users', 'role')) {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['lead', 'developer'])->default('lead')->after('password');
        });
        echo "Column 'role' added to users table successfully.\n";
    } else {
        echo "Column 'role' already exists.\n";
    }

    // Also update existing users to be leads just in case
    DB::table('users')->update(['role' => 'lead']);
    echo "All users updated to 'lead'.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
