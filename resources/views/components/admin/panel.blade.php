@props([
    'title',
    'description' => null,
])

<section class="rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-200/40 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none">
    <header class="border-b border-slate-100 px-5 py-4 dark:border-slate-800 sm:px-6">
        <h2 class="text-base font-semibold text-slate-950 dark:text-white">{{ $title }}</h2>

        @if ($description)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
        @endif
    </header>

    <div class="p-5 sm:p-6">{{ $slot }}</div>
</section>
