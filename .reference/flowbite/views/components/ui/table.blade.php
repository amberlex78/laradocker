@props([
    'variant' => 'default',
    'striped' => false,
    'hoverable' => false,
    'caption' => null,
    'captionSide' => 'top',
])

@php
    $variantClasses = [
        'default' => 'border border-gray-200 shadow-sm dark:border-gray-700',
        'borderless' => '',
        'shadow' => 'border border-gray-200 shadow-md dark:border-gray-700',
    ];

    $tableClasses = [
        'w-full text-left text-sm text-gray-500 dark:text-gray-400',
        $striped ? '[&>tbody>tr:nth-child(even)]:bg-gray-50 dark:[&>tbody>tr:nth-child(even)]:bg-gray-700' : null,
        $hoverable ? '[&>tbody>tr:hover]:bg-gray-50 dark:[&>tbody>tr:hover]:bg-gray-600' : null,
    ];

    $captionClasses = $captionSide === 'bottom' ? 'caption-bottom' : 'caption-top';
@endphp

<div {{ $attributes->class(['relative overflow-x-auto rounded-base', $variantClasses[$variant] ?? $variantClasses['default']]) }}>
    <table class="{{ implode(' ', array_filter($tableClasses)) }}">
        @if ($caption)
            <caption class="{{ $captionClasses }} px-6 py-3 text-left text-sm text-gray-500 dark:text-gray-400">
                {{ $caption }}
            </caption>
        @endif

        {{ $slot }}
    </table>
</div>
