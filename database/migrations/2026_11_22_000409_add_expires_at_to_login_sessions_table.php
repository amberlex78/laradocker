<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('login_sessions', function (Blueprint $table): void {
            $table->timestamp('expires_at')->nullable()->after('last_seen_at');
        });

        $sessionLifetime = (int) config('session.lifetime');

        DB::table('login_sessions')
            ->select(['id', 'last_seen_at'])
            ->whereNull('expires_at')
            ->get()
            ->each(function (object $loginSession) use ($sessionLifetime): void {
                DB::table('login_sessions')
                    ->where('id', $loginSession->id)
                    ->update([
                        'expires_at' => Carbon::parse($loginSession->last_seen_at)->addMinutes($sessionLifetime),
                    ]);
            });

        Schema::table('login_sessions', function (Blueprint $table): void {
            $table->timestamp('expires_at')->nullable(false)->change();
            $table->index(['user_id', 'logged_out_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_sessions', function (Blueprint $table): void {
            $table->dropIndex(['login_sessions_user_id_logged_out_at_expires_at_index']);
            $table->dropColumn('expires_at');
        });
    }
};
