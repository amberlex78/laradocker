<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Developer\UserController as DeveloperUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function (): void {
    Route::view('/account', 'account.index')->name('account');
});

Route::middleware(['auth', 'role:developer,admin,operator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::view('/', 'admin.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::resource('users', AdminUserController::class);
    });

Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function (): void {
        Route::view('/', 'developer.dashboard')->name('dashboard');
    });

Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function (): void {
        Route::resource('users', DeveloperUserController::class);
    });
