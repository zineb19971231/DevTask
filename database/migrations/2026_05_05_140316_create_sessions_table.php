<?php
// This migration is a duplicate of the one in 0001_01_01_000000_create_users_table.php
// Emptying to avoid "Table already exists" error during migration.
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {}
    public function down(): void {}
};
