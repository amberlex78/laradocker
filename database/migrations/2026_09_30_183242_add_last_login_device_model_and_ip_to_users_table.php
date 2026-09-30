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
            $table->string('last_login_device_model')->nullable()->after('last_login_device_type');
            $table->string('last_login_ip_address', 45)->nullable()->after('last_login_browser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'last_login_device_model',
                'last_login_ip_address',
            ]);
        });
    }
};
