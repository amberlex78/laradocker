@props([
    'area' => 'admin',
])

<nav class="sticky top-0 z-30 border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="flex min-w-0 flex-wrap items-center justify-between gap-2 px-3 py-3 sm:gap-3 sm:px-4 lg:px-6">
        <div class="flex min-w-48 flex-1 items-center gap-2 sm:gap-3">
            <button type="button" @click="sidebarOpen = true" aria-controls="{{ $area }}-sidebar" class="shrink-0 rounded-base p-2 text-gray-500 hover:bg-gray-100 focus:ring-1 focus:ring-gray-200 focus:outline-none md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-label="Open sidebar">
                <x-icon name="menu" class="h-6 w-6" />
            </button>

            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $workspace['label'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <div class="ms-auto flex shrink-0 items-center gap-1 sm:gap-2">
            <x-ui.theme-toggle />

            <x-admin.user-role-badge :role="auth()->user()->role" class="inline-flex" />

            <x-user-menu />
        </div>
    </div>
</nav>
