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
