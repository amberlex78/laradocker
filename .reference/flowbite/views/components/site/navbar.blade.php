<header class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-base bg-blue-600 text-lg font-bold text-white">A</span>
            <span class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name', 'Application') }}</span>
        </a>

        <div class="flex items-center gap-2">
            <x-ui.theme-toggle />
            <a href="{{ route('login') }}" class="rounded-base px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-gray-700">Sign in</a>
        </div>
    </nav>
</header>
