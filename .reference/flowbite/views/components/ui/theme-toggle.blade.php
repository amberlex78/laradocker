<button
    x-data
    @click="$store.theme.toggle()"
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'rounded-base p-2.5 text-sm text-gray-500 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-700',
        'aria-label' => 'Toggle color theme',
    ]) }}
>
    <svg x-cloak x-show="! $store.theme.dark" class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
        <path d="M17.8 13.75a1 1 0 0 0-1.04-.9 7.38 7.38 0 0 1-5.43-1.9 7.5 7.5 0 0 1-2.2-5.35A7.7 7.7 0 0 1 9.1 1.57a1 1 0 0 0-1.13-1.25A9.7 9.7 0 1 0 17.8 13.75Z"/>
    </svg>
    <svg x-cloak x-show="$store.theme.dark" class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0-9a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm0-4a1 1 0 0 0 1-1V1a1 1 0 0 0-2 0v1a1 1 0 0 0 1 1Zm0 16a1 1 0 0 0 1-1v-1a1 1 0 0 0-2 0v1a1 1 0 0 0 1 1ZM4.64 5.64a1 1 0 0 0 .7-.29 1 1 0 0 0 0-1.41l-.7-.71a1 1 0 0 0-1.42 1.42l.71.7a1 1 0 0 0 .71.29Zm10.72 8.72a1 1 0 0 0-.71.29 1 1 0 0 0 0 1.41l.71.71a1 1 0 0 0 1.41-1.42l-.7-.7a1 1 0 0 0-.71-.29ZM1 9a1 1 0 0 0-1 1 1 1 0 0 0 1 1h1a1 1 0 0 0 0-2H1Zm18 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2ZM4.64 14.36a1 1 0 0 0-.71.29l-.71.7a1 1 0 0 0 1.42 1.42l.7-.71a1 1 0 0 0 0-1.41 1 1 0 0 0-.7-.29Zm10.72-8.72a1 1 0 0 0 .71-.29l.7-.7a1 1 0 0 0-1.41-1.42l-.71.71a1 1 0 0 0 0 1.41 1 1 0 0 0 .71.29Z"/>
    </svg>
</button>
