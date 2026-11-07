<?php

it('generates secure URLs behind a trusted reverse proxy', function (): void {
    $response = $this->withServerVariables([
        'REMOTE_ADDR' => '127.0.0.1',
    ])->withHeaders([
        'X-Forwarded-Host' => 'laradocker.top',
        'X-Forwarded-Proto' => 'https',
    ])->get('/login');

    $response
        ->assertOk()
        ->assertSee('<svg', false)
        ->assertSee('width="210"', false)
        ->assertSee('height="40"', false)
        ->assertSee('viewBox="0 0 210 40"', false)
        ->assertSee('fill="currentColor"', false)
        ->assertSee('https://laradocker.top/js/filament/forms/components/text-input/password-reveal.js', false)
        ->assertDontSee('/images/laradocker-logo.svg', false)
        ->assertDontSee('/images/laradocker-logo-dark.svg', false)
        ->assertDontSee('http://laradocker.top/js/filament/', false)
        ->assertDontSee('http://laradocker.top/livewire-', false);

    expect($response->getContent())
        ->toMatch('/data-update-uri="https:\/\/laradocker\.top\/livewire-[^"]+\/update"/');
});

it('generates local URLs when the container receives no forwarded headers', function (): void {
    $response = $this->withServerVariables([
        'REMOTE_ADDR' => '127.0.0.1',
    ])->withHeaders([
        'Host' => 'localhost:8000',
    ])->get('/login');

    $response
        ->assertOk()
        ->assertSee('http://localhost:8000/js/filament/forms/components/text-input/password-reveal.js', false)
        ->assertDontSee('/http:/', false)
        ->assertDontSee('http://localhost/livewire-', false);

    expect($response->getContent())
        ->toMatch('/data-update-uri="http:\/\/localhost:8000\/livewire-[^"]+\/update"/');
});
