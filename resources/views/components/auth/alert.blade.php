@props([
    'title' => null,
    'type' => 'error',
])

@php
    $isSuccess = $type === 'success';
    $containerClasses = $isSuccess
        ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-500/30 dark:bg-emerald-500/10'
        : 'border-red-200 bg-red-50 dark:border-red-500/30 dark:bg-red-500/10';
    $iconClasses = $isSuccess ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400';
    $titleClasses = $isSuccess ? 'text-emerald-900 dark:text-emerald-100' : 'text-red-900 dark:text-red-100';
    $bodyClasses = $isSuccess ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border p-4 '.$containerClasses]) }} role="{{ $isSuccess ? 'status' : 'alert' }}">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 shrink-0 {{ $iconClasses }}">
            @if ($isSuccess)
                <x-icon name="circle-check" class="h-5 w-5" />
            @else
                <x-icon name="circle-alert" class="h-5 w-5" />
            @endif
        </div>

        <div class="min-w-0 text-sm {{ $bodyClasses }}">
            @if ($title)
                <p class="mb-1 font-semibold {{ $titleClasses }}">{{ $title }}</p>
            @endif
            <div>{{ $slot }}</div>
        </div>
    </div>
</div>
