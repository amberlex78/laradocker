@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'error' => null,
    'type' => 'text',
])

@php
    $inputId = $attributes->get('id', $name);
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
        </label>
    @endif

    <input
        {{ $attributes->class([
            'block w-full rounded-base border p-2.5 text-sm focus:ring-1 focus:outline-none',
            $error
                ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/20 dark:text-red-400 dark:placeholder-red-400'
                : 'border-gray-300 bg-gray-50 text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400',
        ])->merge(['id' => $inputId, 'name' => $name, 'type' => $type]) }}
    >

    @if ($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif ($hint)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
