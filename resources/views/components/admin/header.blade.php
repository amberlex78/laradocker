@props(['area' => 'admin'])

<header class="sticky top-0 z-20 flex min-h-20 items-center justify-between border-b border-slate-200 bg-white/95 px-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button
            type="button"
            class="rounded-xl border border-slate-200 p-2.5 text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 lg:hidden"
            aria-label="Open navigation"
            aria-controls="{{ $area }}-sidebar"
            @click="sidebarOpen = true"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div>
            <p class="text-sm font-semibold text-slate-950">{{ $area === 'developer' ? 'Technical workspace' : 'Business workspace' }}</p>
            <p class="hidden text-xs text-slate-500 sm:block">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="hidden rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 sm:inline-flex">
            {{ auth()->user()->role->value }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950" type="submit">
                Log out
            </button>
        </form>
    </div>
</header>
