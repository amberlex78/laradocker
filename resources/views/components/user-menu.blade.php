<div class="relative min-w-0 shrink-0" x-data="{ profileMenuOpen: false }" @click.outside="profileMenuOpen = false" @keydown.escape.window="profileMenuOpen = false">
    <button id="admin-user-menu-trigger" type="button" @click="profileMenuOpen = ! profileMenuOpen" :aria-expanded="profileMenuOpen" aria-haspopup="menu" class="inline-flex min-w-0 max-w-40 shrink-0 items-center gap-2 rounded-base px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700 sm:max-w-56">
        <span class="sr-only">Open user menu for {{ auth()->user()->name }}</span>
        <span class="truncate">{{ auth()->user()->name }}</span>
        <x-icon name="chevron-down" class="h-4 w-4 shrink-0 text-gray-500 dark:text-gray-400" />
    </button>

    <div x-cloak x-show="profileMenuOpen" x-transition.origin.top.right class="absolute end-0 top-full z-50 my-4 w-56 list-none divide-y divide-gray-100 rounded-base bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700" role="menu">
        <div class="px-4 py-3">
            <span class="block truncate text-sm text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
            <span class="block truncate text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
        </div>

        <ul class="py-2" aria-labelledby="admin-user-menu-trigger">
            @if (request()->routeIs('admin.*', 'developer.*'))
                <li>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" href="{{ route('home') }}" role="menuitem">
                        <x-icon name="arrow-right" class="h-4 w-4" />
                        View public site
                    </a>
                </li>
            @endif

            @if ($workspace && ! request()->routeIs('admin.*', 'developer.*'))
                <li>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" href="{{ route($workspace['route']) }}" role="menuitem">
                        <x-icon name="layout-dashboard" class="h-4 w-4" />
                        {{ $workspace['label'] }}
                    </a>
                </li>
            @endif

            <li>
                <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" href="{{ route('account') }}" role="menuitem">
                    <x-icon name="user-round" class="h-4 w-4" />
                    My profile
                </a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" type="submit" role="menuitem">
                        <x-icon name="log-out" class="h-4 w-4" />
                        Log out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
