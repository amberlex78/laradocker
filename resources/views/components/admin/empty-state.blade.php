@props([
    'title',
    'description',
])

<div class="flex min-h-36 flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center dark:border-slate-700 dark:bg-slate-950">
    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-400 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700" aria-hidden="true">
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </span>
    <h3 class="mt-3 text-sm font-semibold text-slate-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1 max-w-sm text-sm leading-5 text-slate-500 dark:text-slate-400">{{ $description }}</p>
</div>
