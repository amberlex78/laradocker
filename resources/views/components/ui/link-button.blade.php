@props([
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $variantClasses = match ($variant) {
        'primary' => 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20 hover:bg-indigo-700 focus:ring-indigo-500/30',
        'secondary' => 'bg-white text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700 dark:hover:bg-slate-700/30',
        'danger' => 'border border-rose-200 text-rose-600 hover:bg-rose-50 focus:ring-rose-500/30 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10',
        default => throw new InvalidArgumentException("Unsupported link button variant [{$variant}]."),
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3.5 text-sm',
        default => throw new InvalidArgumentException("Unsupported link button size [{$size}]."),
    };
@endphp

<a
    {{ $attributes->class([
        'inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition focus:outline-none focus:ring-2',
        $variantClasses,
        $sizeClasses,
    ]) }}
>
    {{ $slot }}
</a>
