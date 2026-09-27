@props([
    'id',
    'label',
])

<div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" {{ $attributes->class(['relative inline-block text-left']) }}>
    <button id="{{ $id }}-trigger" @click="open = ! open" :aria-expanded="open" type="button" class="inline-flex items-center rounded-base bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-900 shadow-sm ring-1 ring-gray-200 hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
        {{ $label }}
        <svg class="ms-3 h-2.5 w-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>

    <div id="{{ $id }}" x-cloak x-show="open" x-transition.origin.top.left class="absolute z-10 mt-2 w-44 divide-y divide-gray-100 rounded-base bg-white shadow dark:divide-gray-600 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $id }}-trigger">
            {{ $slot }}
        </ul>
    </div>
</div>
