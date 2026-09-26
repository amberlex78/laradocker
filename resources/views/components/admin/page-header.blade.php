@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'icon' => null,
])

<div class="flex flex-col gap-2">
    @if ($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">{{ $eyebrow }}</p>
    @endif

    <h1 class="inline-flex items-center gap-3 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-3xl">
        @if ($icon)
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-300" aria-hidden="true">
                <x-icon :name="$icon" class="h-5 w-5" />
            </span>
        @endif
        {{ $title }}
    </h1>

    @if ($description)
        <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400">{{ $description }}</p>
    @endif
</div>
