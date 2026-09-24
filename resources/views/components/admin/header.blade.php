@props(['area' => 'admin'])

<header class="sticky top-0 z-20 flex min-h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-950/95 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button
            type="button"
            class="rounded-xl border border-slate-200 p-2.5 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white lg:hidden"
            aria-label="Open navigation"
            aria-controls="{{ $area }}-sidebar"
            @click="sidebarOpen = true"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $area === 'developer' ? 'Technical workspace' : 'Business workspace' }}</p>
            <p class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button
            type="button"
            class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
            aria-label="Toggle theme"
            title="Toggle theme"
            :aria-pressed="darkMode"
            @click="darkMode = !darkMode"
        >
            <svg x-cloak x-show="!darkMode" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <circle cx="12" cy="12" r="4" />
                <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
            </svg>
            <svg x-cloak x-show="darkMode" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
            </svg>
        </button>

        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300 sm:inline-flex">
            {{ auth()->user()->role->value }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white" type="submit">
                Log out
            </button>
        </form>
    </div>
</header>
