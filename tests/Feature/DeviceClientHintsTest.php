<?php

it('advertises device client hints', function (): void {
    $this->get('/')->assertOk()
        ->assertHeader('Accept-CH', 'Sec-CH-UA-Model, Sec-CH-UA-Form-Factors')
        ->assertHeader('Permissions-Policy', 'ch-ua-model=(self), ch-ua-form-factors=(self)');
});
