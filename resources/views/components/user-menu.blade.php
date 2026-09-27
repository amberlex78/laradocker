@php
    $workspaceRoute = match (auth()->user()->role) {
        \App\Enums\UserRole::Developer => 'developer.dashboard',
        \App\Enums\UserRole::Admin, \App\Enums\UserRole::Operator => 'admin.dashboard',
        default => null,
    };
@endphp

<div class="relative" x-data="{ profileMenuOpen: false }" @click.outside="profileMenuOpen = false" @keydown.escape.window="profileMenuOpen = false">
    <button id="admin-user-menu-trigger" type="button" @click="profileMenuOpen = ! profileMenuOpen" :aria-expanded="profileMenuOpen" aria-haspopup="menu" class="flex rounded-full bg-gray-800 text-sm focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
        <span class="sr-only">Open user menu</span>
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 font-semibold text-white">
            {{ str(auth()->user()->name)->substr(0, 1)->upper() }}
        </span>
    </button>

    <div x-cloak x-show="profileMenuOpen" x-transition.origin.top.right class="absolute end-0 top-full z-50 my-4 w-56 list-none divide-y divide-gray-100 rounded-base bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700" role="menu">
        <div class="px-4 py-3">
            <span class="block truncate text-sm text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>
            <span class="block truncate text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
        </div>

        <ul class="py-2" aria-labelledby="admin-user-menu-trigger">
            @if ($workspaceRoute && ! request()->routeIs('admin.*', 'developer.*'))
                <li>
                    <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" href="{{ route($workspaceRoute) }}" role="menuitem">
                        <x-icon name="layout-dashboard" class="h-4 w-4" />
                        Back to workspace
                    </a>
                </li>
            @endif

            <li>
                <a class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600" href="{{ route('account') }}" role="menuitem">
                    <x-icon name="user-round" class="h-4 w-4" />
                    Profile
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
