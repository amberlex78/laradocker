<?php

use App\Models\User;
use App\Validation\AuthValidationRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('registration rules reject a duplicate email and mismatched password confirmation', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $validator = Validator::make([
        'name' => 'New User',
        'email' => 'taken@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ], app(AuthValidationRules::class)->registration());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});

test('profile rules allow the user to keep the current email address', function () {
    $user = User::factory()->create(['email' => 'current@example.com']);

    $validator = Validator::make([
        'name' => 'Updated User',
        'email' => 'current@example.com',
    ], app(AuthValidationRules::class)->profile($user));

    expect($validator->passes())->toBeTrue();
});

test('password update rules reject an incorrect current password', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $validator = Validator::make([
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ], app(AuthValidationRules::class)->passwordUpdate());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('current_password'))->toBeTrue();
});
