@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'rows' => 4,
])

@php
    $textareaId = $attributes->get('id', $name);
@endphp

<div class="space-y-2">
    @if ($label)
        <label for="{{ $textareaId }}" class="block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
        </label>
    @endif

    <textarea
        {{ $attributes->class(['block w-full rounded-base border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400'])->merge(['id' => $textareaId, 'name' => $name, 'rows' => $rows]) }}
    >{{ $slot }}</textarea>

    @if ($hint)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif
</div>
