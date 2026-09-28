<button
    x-data
    @click="$store.theme.toggle()"
    @pointerup="$event.currentTarget.blur()"
    {{ $attributes->class([
        'rounded-lg p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-300 dark:text-white dark:hover:bg-gray-700 dark:focus-visible:ring-white/50',
    ])->merge([
        'type' => 'button',
        'aria-label' => 'Toggle theme',
        'title' => 'Toggle theme',
    ]) }}
>
    <x-icon name="moon" x-cloak x-show="! $store.theme.dark" class="h-5 w-5" />
    <x-icon name="sun" x-cloak x-show="$store.theme.dark" class="h-5 w-5" />
</button>
