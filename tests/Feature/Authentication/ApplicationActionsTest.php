<?php

use App\Enums\UserRole;

test('each role exposes its landing route', function (UserRole $role, string $route): void {
    expect($role->landingRoute())->toBe($route);
})->with([
    'developer' => [UserRole::Developer, 'developer.dashboard'],
    'admin' => [UserRole::Admin, 'admin.dashboard'],
    'operator' => [UserRole::Operator, 'admin.dashboard'],
    'user' => [UserRole::User, 'account'],
]);
