@props([
    'area' => 'admin',
    'title' => 'Dashboard',
])

<nav class="sticky top-0 z-30 border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between px-4 py-3 lg:px-6">
        <div class="flex items-center gap-3">
            <button type="button" @click="sidebarOpen = true" aria-controls="{{ $area }}-sidebar" class="rounded-base p-2 text-gray-500 hover:bg-gray-100 focus:ring-1 focus:ring-gray-200 focus:outline-none md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-label="Open sidebar">
                <x-icon name="menu" class="h-6 w-6" />
            </button>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ ucfirst($area) }} area</p>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-ui.theme-toggle />

            <span class="hidden rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300 sm:inline-flex">
                {{ auth()->user()->role->value }}
            </span>

            <x-user-menu />
        </div>
    </div>
</nav>
