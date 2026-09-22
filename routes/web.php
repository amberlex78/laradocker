<?php

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

Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function (): void {
        Route::view('/', 'developer.dashboard')->name('dashboard');
    });
