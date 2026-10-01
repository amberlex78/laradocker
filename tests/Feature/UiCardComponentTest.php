<?php

test('ui card keeps shared spacing between its header and content', function (): void {
    $this->blade(
        <<<'BLADE'
        <x-ui.card title="Title" description="Description">
            <x-slot:actions>
                <button type="button">Action</button>
            </x-slot:actions>

            Content
        </x-ui.card>
        BLADE
    )
        ->assertSeeHtml('class="flex flex-col gap-6 rounded-base border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"')
        ->assertSeeHtml('class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"')
        ->assertSeeHtml('class="mt-2 text-sm text-gray-500 dark:text-gray-400"')
        ->assertSeeText('Content');
});
