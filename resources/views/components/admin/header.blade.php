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
            <x-icon name="menu" class="h-5 w-5" />
        </button>

        <div>
            <p class="text-sm font-semibold text-slate-950 dark:text-white">{{ $area === 'developer' ? 'Developer workspace' : 'Admin workspace' }}</p>
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
            <x-icon name="sun" x-cloak x-show="!darkMode" class="h-5 w-5" />
            <x-icon name="moon" x-cloak x-show="darkMode" class="h-5 w-5" />
        </button>

        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300 sm:inline-flex">
            {{ auth()->user()->role->value }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white" type="submit">
                <span class="inline-flex items-center gap-2">
                    <x-icon name="log-out" class="h-4 w-4" />
                    Log out
                </span>
            </button>
        </form>
    </div>
</header>
