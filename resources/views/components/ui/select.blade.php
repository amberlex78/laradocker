@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'error' => null,
])

@php
    $selectId = $attributes->get('id', $name);
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
        </label>
    @endif

    <select
        {{ $attributes->class([
            $error
                ? 'block w-full rounded-base border border-red-500 bg-red-50 p-2.5 text-sm text-red-900 focus:border-red-500 focus:ring-red-500 focus:outline-none dark:border-red-400 dark:bg-red-900/20 dark:text-red-400'
                : 'block w-full rounded-base border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400',
        ])->merge(['id' => $selectId, 'name' => $name]) }}
    >
        {{ $slot }}
    </select>

    @if ($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif ($hint)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
