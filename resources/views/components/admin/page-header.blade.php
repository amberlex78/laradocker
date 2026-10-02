@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<div class="mb-8 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
    <div>
        @if ($eyebrow)
            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ $eyebrow }}</p>
        @endif

        <h1 class="inline-flex min-w-0 flex-wrap items-center gap-3 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            @if ($icon)
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-base bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300" aria-hidden="true">
                    <x-icon :name="$icon" class="h-5 w-5" />
                </span>
            @endif
            {{ $title }}
        </h1>

        @if ($description)
            <p class="mt-2 max-w-2xl text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>
