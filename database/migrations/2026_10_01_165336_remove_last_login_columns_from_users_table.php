<?php

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
        DB::table('users')
            ->whereNotNull('last_login_at')
            ->orderBy('id')
            ->eachById(function (object $user): void {
                $historyExists = DB::table('login_histories')
                    ->where('user_id', $user->id)
                    ->where('logged_in_at', $user->last_login_at)
                    ->exists();

                if ($historyExists) {
                    return;
                }

                DB::table('login_histories')->insert([
                    'user_id' => $user->id,
                    'logged_in_at' => $user->last_login_at,
                    'ip_address' => $user->last_login_ip_address,
                    'device_type' => $user->last_login_device_type,
                    'device_model' => $user->last_login_device_model,
                    'operating_system' => $user->last_login_os,
                    'browser' => $user->last_login_browser,
                    'browser_version' => $user->last_login_browser_version,
                ]);
            });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'last_login_device_type',
                'last_login_device_model',
                'last_login_os',
                'last_login_browser',
                'last_login_browser_version',
                'last_login_ip_address',
                'last_login_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('last_login_device_type')->nullable();
            $table->string('last_login_device_model')->nullable();
            $table->string('last_login_os')->nullable();
            $table->string('last_login_browser')->nullable();
            $table->string('last_login_browser_version')->nullable();
            $table->string('last_login_ip_address', 45)->nullable();
            $table->timestamp('last_login_at')->nullable();
        });

        DB::table('users')
            ->orderBy('id')
            ->eachById(function (object $user): void {
                $latestLogin = DB::table('login_histories')
                    ->where('user_id', $user->id)
                    ->orderByDesc('logged_in_at')
                    ->orderByDesc('id')
                    ->first();

                if ($latestLogin === null) {
                    return;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'last_login_device_type' => $latestLogin->device_type,
                        'last_login_device_model' => $latestLogin->device_model,
                        'last_login_os' => $latestLogin->operating_system,
                        'last_login_browser' => $latestLogin->browser,
                        'last_login_browser_version' => $latestLogin->browser_version,
                        'last_login_ip_address' => $latestLogin->ip_address,
                        'last_login_at' => $latestLogin->logged_in_at,
                    ]);
            });
    }
};
