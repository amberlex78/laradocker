<?php

test('ui button renders the primary TailAdmin-inspired style and forwards attributes', function (): void {
    $this->blade(
        '<x-ui.button type="submit" variant="primary" size="md" aria-label="Save">Save</x-ui.button>'
    )
        ->assertSeeHtml('type="submit"')
        ->assertSeeHtml('aria-label="Save"')
        ->assertSeeHtml('bg-indigo-600')
        ->assertSeeHtml('shadow-indigo-600/20')
        ->assertSeeText('Save');
});

test('ui button renders the secondary variant and large size', function (): void {
    $this->blade(
        '<x-ui.button variant="secondary" size="lg">Cancel</x-ui.button>'
    )
        ->assertSeeHtml('bg-white')
        ->assertSeeHtml('px-5')
        ->assertSeeHtml('py-3.5')
        ->assertSeeHtml('ring-1')
        ->assertSeeText('Cancel');
});

test('ui button renders the danger variant', function (): void {
    $this->blade(
        '<x-ui.button type="submit" variant="danger">Delete user</x-ui.button>'
    )
        ->assertSeeHtml('border-rose-200')
        ->assertSeeHtml('text-rose-600')
        ->assertSeeText('Delete user');
});
