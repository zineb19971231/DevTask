<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::table('projets')->first();
    echo "Table 'projets' exists." . PHP_EOL;
} catch (\Exception $e) {
    echo "Table 'projets' DOES NOT exist: " . $e->getMessage() . PHP_EOL;
}

try {
    DB::table('projects')->first();
    echo "Table 'projects' exists." . PHP_EOL;
} catch (\Exception $e) {
    echo "Table 'projects' DOES NOT exist: " . $e->getMessage() . PHP_EOL;
}

try {
    DB::table('users')->first();
    echo "Table 'users' exists." . PHP_EOL;
} catch (\Exception $e) {
    echo "Table 'users' DOES NOT exist: " . $e->getMessage() . PHP_EOL;
}
