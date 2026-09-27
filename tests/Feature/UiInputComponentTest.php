<?php

test('ui input renders the standard Flowbite field and forwards attributes', function (): void {
    $this->blade(
        '<x-ui.input id="email" name="email" type="email" label="Email" value="user@example.com" placeholder="you@example.com" required />'
    )
        ->assertSeeHtml('id="email"')
        ->assertSeeHtml('name="email"')
        ->assertSeeHtml('type="email"')
        ->assertSeeHtml('value="user@example.com"')
        ->assertSeeHtml('focus:ring-blue-500')
        ->assertSeeText('Email');
});

test('ui password input renders an accessible visibility toggle', function (): void {
    $this->blade(
        '<x-ui.input id="password" name="password" type="password" label="Password" />'
    )
        ->assertSeeHtml('x-data="{ passwordVisible: false }"')
        ->assertSeeHtml('x-bind:type="passwordVisible ? \'text\' : \'password\'"')
        ->assertSeeHtml('type="button"')
        ->assertSeeHtml(':aria-label="passwordVisible ? \'Hide password\' : \'Show password\'"')
        ->assertSeeHtml(':aria-pressed="passwordVisible"')
        ->assertSeeHtml('data-icon="eye"')
        ->assertSeeHtml('data-icon="eye-off"');
});
