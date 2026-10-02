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

test('the production host proxy restores Cloudflare visitor addresses before forwarding', function (): void {
    $contents = file_get_contents(base_path('docker/nginx/host/prod-cloudflare.conf.example'));
    $cloudflareNetworks = [
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '172.64.0.0/13',
        '131.0.72.0/22',
        '2400:cb00::/32',
        '2606:4700::/32',
        '2803:f800::/32',
        '2405:b500::/32',
        '2405:8100::/32',
        '2a06:98c0::/29',
        '2c0f:f248::/32',
    ];

    expect($contents)
        ->toContain('real_ip_header CF-Connecting-IP;')
        ->toContain('real_ip_recursive on;')
        ->toContain('proxy_set_header X-Forwarded-For $remote_addr;')
        ->not->toContain('proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;');

    foreach ($cloudflareNetworks as $cloudflareNetwork) {
        expect($contents)->toContain("set_real_ip_from {$cloudflareNetwork};");
    }
});
