@props([
    'eyebrow' => null,
    'title',
    'description' => null,
])

<div class="flex flex-col gap-2">
    @if ($eyebrow)
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">{{ $eyebrow }}</p>
    @endif

    <h1 class="text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $title }}</h1>

    @if ($description)
        <p class="max-w-2xl text-sm leading-6 text-slate-600">{{ $description }}</p>
    @endif
</div>
