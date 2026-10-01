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
        Schema::table('login_histories', function (Blueprint $table): void {
            $table->string('session_id')->nullable()->after('user_id')->index();
            $table->timestamp('logged_out_at')->nullable()->after('logged_in_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table): void {
            $table->dropIndex(['session_id']);
            $table->dropColumn(['session_id', 'logged_out_at']);
        });
    }
};
