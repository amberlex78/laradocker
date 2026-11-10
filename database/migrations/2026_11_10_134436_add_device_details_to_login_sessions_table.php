<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('login_sessions', function (Blueprint $table): void {
            $table->string('client')->nullable()->after('user_agent');
            $table->string('operating_system')->nullable()->after('client');
            $table->string('device')->nullable()->after('operating_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_sessions', function (Blueprint $table): void {
            $table->dropColumn(['client', 'operating_system', 'device']);
        });
    }
};
