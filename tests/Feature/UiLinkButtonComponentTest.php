<?php

test('ui link button renders an anchor with the shared primary style', function (): void {
    $this->blade(
        '<x-ui.link-button href="/admin/users/create" variant="primary" aria-label="Add user">Add user</x-ui.link-button>'
    )
        ->assertSeeHtml('<a')
        ->assertSeeHtml('href="/admin/users/create"')
        ->assertSeeHtml('aria-label="Add user"')
        ->assertSeeHtml('bg-indigo-600')
        ->assertSeeHtml('shadow-indigo-600/20')
        ->assertSeeText('Add user');
});
