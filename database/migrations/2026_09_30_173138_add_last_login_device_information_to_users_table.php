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
        Schema::table('users', function (Blueprint $table): void {
            $table->string('last_login_device_type')->nullable()->after('role');
            $table->string('last_login_os')->nullable()->after('last_login_device_type');
            $table->string('last_login_browser')->nullable()->after('last_login_os');
            $table->timestamp('last_login_at')->nullable()->after('last_login_browser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'last_login_device_type',
                'last_login_os',
                'last_login_browser',
                'last_login_at',
            ]);
        });
    }
};
