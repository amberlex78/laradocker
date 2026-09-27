@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $variantClasses = [
        'primary' => 'bg-blue-700 text-white hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800',
        'secondary' => 'border border-gray-200 bg-white text-gray-900 hover:bg-gray-100 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700',
        'success' => 'bg-green-700 text-white hover:bg-green-800 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800',
        'danger' => 'bg-red-700 text-white hover:bg-red-800 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900',
        'ghost' => 'text-gray-900 hover:bg-gray-100 focus:ring-gray-100 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];
@endphp

<button
    {{ $attributes->class([
        'inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-base font-medium focus:ring-4 focus:outline-none',
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $sizeClasses[$size] ?? $sizeClasses['md'],
    ])->merge(['type' => $type]) }}
>
    {{ $slot }}
</button>
