<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

it('labels the remember me option with its two-week duration', function (string $locale, string $label): void {
    $originalLocale = app()->getLocale();

    app()->setLocale($locale);

    try {
        $this->get('/login')
            ->assertOk()
            ->assertSee($label);
    } finally {
        app()->setLocale($originalLocale);
    }
})->with([
    'Ukrainian' => ['uk', 'Запам’ятати мене на 2 тижні'],
    'English' => ['en', 'Remember me for 2 weeks'],
]);

it('sets the remember me cookie to expire after two weeks', function (): void {
    $user = User::factory()->create();
    $expectedExpiration = now()->addDays(14)->timestamp;

    Auth::guard('web')->login($user, remember: true);

    $cookie = Cookie::queued(Auth::guard('web')->getRecallerName());

    expect($cookie)->not->toBeNull()
        ->and($cookie->getExpiresTime())->toBe($expectedExpiration);
});
