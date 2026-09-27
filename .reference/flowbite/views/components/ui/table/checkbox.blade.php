@props([
    'id',
    'label' => 'Select row',
    'name' => null,
    'value' => null,
])

<div class="flex items-center">
    <input
        {{ $attributes->class(['h-4 w-4 rounded border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600'])->merge(array_filter([
            'id' => $id,
            'name' => $name,
            'type' => 'checkbox',
            'value' => $value,
        ], fn ($attribute) => $attribute !== null)) }}
    >
    <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
</div>
