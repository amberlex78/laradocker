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
        ->assertDontSee('passwordVisible')
        ->assertDontSee('data-icon="eye"')
        ->assertSeeText('Email');
});

test('ui password input renders an accessible visibility toggle', function (): void {
    $this->blade(
        '<x-ui.password-input id="password" name="password" label="Password" />'
    )
        ->assertSeeHtml('x-data="{ passwordVisible: false }"')
        ->assertSeeHtml('x-bind:type="passwordVisible ? \'text\' : \'password\'"')
        ->assertSeeHtml('pe-11')
        ->assertSeeHtml('w-11')
        ->assertSeeHtml('justify-center')
        ->assertSeeHtml('p-0')
        ->assertSeeHtml('type="button"')
        ->assertSeeHtml(':aria-label="passwordVisible ? \'Hide password\' : \'Show password\'"')
        ->assertSeeHtml(':aria-pressed="passwordVisible"')
        ->assertSeeHtml('@pointerup="$event.currentTarget.blur()"')
        ->assertSeeHtml('focus-visible:ring-2')
        ->assertSeeHtml('focus-visible:ring-blue-400')
        ->assertSeeHtml('dark:focus-visible:ring-blue-500')
        ->assertDontSee('focus:ring-2 focus:ring-blue-500', false)
        ->assertSeeHtml('data-icon="eye"')
        ->assertSeeHtml('data-icon="eye-off"');
});
