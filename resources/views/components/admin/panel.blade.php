@props([
    'title',
    'description' => null,
])

<section class="rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/40">
    <header class="border-b border-slate-100 px-5 py-4 sm:px-6">
        <h2 class="text-base font-semibold text-slate-950">{{ $title }}</h2>

        @if ($description)
            <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
        @endif
    </header>

    <div class="p-5 sm:p-6">{{ $slot }}</div>
</section>
