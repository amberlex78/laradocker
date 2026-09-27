@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-blue-700 text-white hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
        'secondary' => 'border border-gray-200 bg-white text-gray-900 hover:bg-gray-100 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700',
        'danger' => 'bg-red-700 text-white hover:bg-red-800 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
        default => throw new InvalidArgumentException("Unsupported button variant [{$variant}]."),
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3.5 text-sm',
        default => throw new InvalidArgumentException("Unsupported button size [{$size}]."),
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-base font-medium transition focus:ring-4 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60',
        $variantClasses,
        $sizeClasses,
    ]) }}
>
    {{ $slot }}
</button>
