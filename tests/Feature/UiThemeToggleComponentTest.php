<?php

test('ui theme toggle keeps focus visible for keyboard navigation only', function (): void {
    $this->blade('<x-ui.theme-toggle />')
        ->assertSeeHtml('@pointerup="$event.currentTarget.blur()"')
        ->assertSeeHtml('focus-visible:ring-2')
        ->assertSeeHtml('focus-visible:ring-gray-300')
        ->assertSeeHtml('dark:focus-visible:ring-white/50')
        ->assertSeeHtml('dark:text-white')
        ->assertDontSee('focus:ring-', false)
        ->assertDontSee('dark:text-gray-400', false)
        ->assertDontSee('white-300', false)
        ->assertSeeHtml('$store.theme.toggle()');
});
