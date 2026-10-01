<?php

test('feature tests use an isolated in-memory sqlite database', function () {
    expect(config('app.env'))->toBe('testing')
        ->and(config('database.default'))->toBe('sqlite')
        ->and(config('database.connections.sqlite.database'))->toBe(':memory:')
        ->and(config('cache.default'))->toBe('array')
        ->and(config('session.driver'))->toBe('array')
        ->and(config('queue.default'))->toBe('sync');
});

test('the application timezone follows APP_TIMEZONE', function (): void {
    expect(config('app.timezone'))->toBe('Europe/Kyiv')
        ->and(date_default_timezone_get())->toBe('Europe/Kyiv');
});

test('environment templates never trust arbitrary forwarding proxies', function (): void {
    foreach (['.env.example', '.env.prod.example'] as $environmentTemplate) {
        $contents = file_get_contents(base_path($environmentTemplate));

        expect($contents)
            ->toContain('TRUSTED_PROXIES=REMOTE_ADDR')
            ->not->toContain('TRUSTED_PROXIES=*');
    }
});
