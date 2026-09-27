@props([
    'id',
    'title',
    'size' => 'md',
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
    ];
@endphp

<div
    id="{{ $id }}"
    x-data="{ open: false }"
    @open-modal.window="if ($event.detail === '{{ $id }}') open = true"
    @close-modal.window="if ($event.detail === '{{ $id }}') open = false"
    @keydown.escape.window="open = false"
    x-cloak
    x-show="open"
    x-transition.opacity
    @click.self="$dispatch('close-modal', '{{ $id }}')"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    class="fixed inset-0 z-50 flex h-full max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 p-4 dark:bg-gray-900/80"
>
    <div class="relative flex min-h-full w-full items-center justify-center">
        <div class="relative w-full {{ $sizeClasses[$size] ?? $sizeClasses['md'] }} rounded-base bg-white shadow dark:bg-gray-700">
            <div class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-600 md:p-5">
                <h3 id="{{ $id }}-title" class="text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                <button type="button" @click="$dispatch('close-modal', '{{ $id }}')" class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-base bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white" aria-label="Close modal">
                    <x-icon name="x" class="h-3 w-3" />
                </button>
            </div>

            <div class="space-y-4 p-4 md:p-5">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <div class="flex items-center gap-3 rounded-b border-t border-gray-200 p-4 dark:border-gray-600 md:p-5">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
