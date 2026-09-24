@props([
    'label',
    'value',
    'description',
    'tone' => 'indigo',
])

@php
    $toneClasses = match ($tone) {
        'emerald' => 'bg-emerald-50 text-emerald-700',
        'amber' => 'bg-amber-50 text-amber-700',
        'slate' => 'bg-slate-100 text-slate-700',
        default => 'bg-indigo-50 text-indigo-700',
    };
@endphp

<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-200/40">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-3 text-2xl font-semibold tracking-tight text-slate-950">{{ $value }}</p>
        </div>

        <span class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold {{ $toneClasses }}" aria-hidden="true">•</span>
    </div>

    <p class="mt-4 text-xs leading-5 text-slate-500">{{ $description }}</p>
</article>
