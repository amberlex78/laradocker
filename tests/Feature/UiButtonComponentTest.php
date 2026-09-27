<?php

test('ui button renders the primary Flowbite style and forwards attributes', function (): void {
    $this->blade(
        '<x-ui.button type="submit" variant="primary" size="md" aria-label="Save">Save</x-ui.button>'
    )
        ->assertSeeHtml('type="submit"')
        ->assertSeeHtml('aria-label="Save"')
        ->assertSeeHtml('bg-blue-700')
        ->assertSeeHtml('focus:ring-blue-300')
        ->assertSeeText('Save');
});

test('ui button renders the secondary variant and large size', function (): void {
    $this->blade(
        '<x-ui.button variant="secondary" size="lg">Cancel</x-ui.button>'
    )
        ->assertSeeHtml('bg-white')
        ->assertSeeHtml('px-5')
        ->assertSeeHtml('py-3')
        ->assertSeeHtml('border-gray-200')
        ->assertSeeText('Cancel');
});

test('ui button renders the danger variant', function (): void {
    $this->blade(
        '<x-ui.button type="submit" variant="danger">Delete user</x-ui.button>'
    )
        ->assertSeeHtml('bg-red-700')
        ->assertSeeHtml('text-white')
        ->assertSeeText('Delete user');
});
