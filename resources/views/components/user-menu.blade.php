@php
    $workspaceRoute = match (auth()->user()->role) {
        \App\Enums\UserRole::Developer => 'developer.dashboard',
        \App\Enums\UserRole::Admin, \App\Enums\UserRole::Operator => 'admin.dashboard',
        default => null,
    };
@endphp

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
            @if ($workspaceRoute && ! request()->routeIs('admin.*', 'developer.*'))
                <a
                    class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white"
                    href="{{ route($workspaceRoute) }}"
                    role="menuitem"
                >
                    <x-icon name="layout-dashboard" class="h-4 w-4 text-slate-400 group-hover:text-slate-700 dark:text-slate-500 dark:group-hover:text-slate-200" />
                    Back to workspace
                </a>
            @endif

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
