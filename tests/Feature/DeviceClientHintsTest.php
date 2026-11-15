<?php

it('advertises the device model client hint', function (): void {
    $this->get('/')->assertOk()
        ->assertHeader('Accept-CH', 'Sec-CH-UA-Model')
        ->assertHeader('Permissions-Policy', 'ch-ua-model=(self)');
});
