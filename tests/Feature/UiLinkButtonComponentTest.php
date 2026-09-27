<?php

test('ui link button renders an anchor with the shared primary style', function (): void {
    $this->blade(
        '<x-ui.link-button href="/admin/users/create" variant="primary" aria-label="Add user">Add user</x-ui.link-button>'
    )
        ->assertSeeHtml('<a')
        ->assertSeeHtml('href="/admin/users/create"')
        ->assertSeeHtml('aria-label="Add user"')
        ->assertSeeHtml('bg-blue-700')
        ->assertSeeHtml('focus:ring-blue-300')
        ->assertSeeText('Add user');
});

test('ui link button renders a light variant for colored backgrounds', function (): void {
    $this->blade(
        '<x-ui.link-button href="/register" variant="light">Create workspace</x-ui.link-button>'
    )
        ->assertSeeHtml('bg-white')
        ->assertSeeHtml('text-blue-700')
        ->assertSeeText('Create workspace');
});
