@props([
    'as' => 'td',
    'variant' => null,
    'scope' => null,
])

@php
    $variant ??= $as === 'th' ? 'row-header' : 'data';

    $variantClasses = [
        'header' => 'px-6 py-3',
        'row-header' => 'whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white',
        'data' => 'px-6 py-4',
    ];

    $cellAttributes = $scope ? ['scope' => $scope] : [];
@endphp

<{{ $as }} {{ $attributes->class([$variantClasses[$variant] ?? $variantClasses['data']])->merge($cellAttributes) }}>
    {{ $slot }}
</{{ $as }}>
