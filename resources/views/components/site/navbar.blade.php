@props([
    'showUserMenu' => true,
])

<header class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-base bg-blue-600 text-lg font-bold text-white">L</span>
            <span class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name', 'Application') }}</span>
        </a>

        <div class="flex items-center gap-2">
            <x-ui.theme-toggle />

            @auth
                @if ($showUserMenu)
                    <x-user-menu />
                @else
                    <a href="{{ route('account') }}" class="inline-flex items-center gap-1.5 rounded-base px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                        <x-icon name="user-round" class="h-4 w-4" />
                        Account
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-base px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-gray-700">
                    <x-icon name="log-in" class="h-4 w-4" />
                    Log in
                </a>
                <x-ui.link-button href="{{ route('register') }}" size="sm">
                    <x-icon name="user-plus" class="h-4 w-4" />
                    Register
                </x-ui.link-button>
            @endauth
        </div>
    </nav>
</header>
