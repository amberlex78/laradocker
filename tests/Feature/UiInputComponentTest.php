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
