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

        <div
            class="relative"
            x-data="{ profileMenuOpen: false }"
            @click.outside="profileMenuOpen = false"
            @keydown.escape.window="profileMenuOpen = false"
        >
            <button
                type="button"
                class="group flex items-center gap-2 rounded-xl px-2 py-1.5 text-left transition hover:bg-slate-100 dark:hover:bg-slate-800"
                aria-haspopup="menu"
                :aria-expanded="profileMenuOpen"
                @click="profileMenuOpen = !profileMenuOpen"
            >
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200">
                    {{ str(auth()->user()->name)->substr(0, 1)->upper() }}
                </span>
                <span class="hidden max-w-40 truncate text-sm font-medium text-slate-700 dark:text-slate-200 sm:block">
                    {{ auth()->user()->name }}
                </span>
                <x-icon name="chevron-down" class="h-4 w-4 text-slate-500 transition-transform group-hover:text-slate-700 dark:text-slate-400 dark:group-hover:text-slate-200" x-cloak x-bind:class="{ 'rotate-180': profileMenuOpen }" />
            </button>

            <div
                x-cloak
                x-show="profileMenuOpen"
                x-transition.origin.top.right
                class="absolute right-0 top-full z-50 mt-3 w-64 rounded-2xl border border-slate-200 bg-white p-3 shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none"
                role="menu"
            >
                <div class="border-b border-slate-100 px-3 pb-3 dark:border-slate-800">
                    <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                </div>

                <div class="flex flex-col gap-1 pt-3">
                    <a
                        class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white"
                        href="{{ route('account') }}"
                        role="menuitem"
                    >
                        <x-icon name="user-round" class="h-4 w-4 text-slate-400 group-hover:text-slate-700 dark:text-slate-500 dark:group-hover:text-slate-200" />
                        Profile
                    </a>

                    <div class="mt-2 border-t border-slate-100 pt-2 dark:border-slate-800">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="group flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white"
                                type="submit"
                                role="menuitem"
                            >
                                <x-icon name="log-out" class="h-4 w-4 text-slate-400 group-hover:text-slate-700 dark:text-slate-500 dark:group-hover:text-slate-200" />
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
