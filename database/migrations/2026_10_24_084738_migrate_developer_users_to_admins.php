<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'developer')
            ->update(['role' => 'admin']);
    }

    /**
     * This data migration is intentionally irreversible because converted
     * administrators cannot be distinguished from existing administrators.
     */
    public function down(): void {}
};
