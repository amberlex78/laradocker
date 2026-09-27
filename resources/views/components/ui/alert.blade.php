@props([
    'variant' => 'info',
    'title' => null,
])

@php
    $variantClasses = [
        'info' => 'border-blue-300 bg-blue-50 text-blue-800 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'success' => 'border-green-300 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-400',
        'warning' => 'border-yellow-300 bg-yellow-50 text-yellow-800 dark:border-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
        'danger' => 'border-red-300 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400',
    ];

    $icon = $variant === 'success' ? 'circle-check' : 'circle-alert';
@endphp

<div {{ $attributes->class(['flex items-start gap-3 rounded-base border p-4 text-sm', $variantClasses[$variant] ?? $variantClasses['info']]) }} role="{{ $variant === 'success' ? 'status' : 'alert' }}">
    <x-icon name="{{ $icon }}" class="mt-0.5 h-5 w-5 shrink-0" />

    <div>
        @if ($title)
            <h3 class="mb-1 font-semibold">{{ $title }}</h3>
        @endif

        <div>{{ $slot }}</div>
    </div>
</div>
