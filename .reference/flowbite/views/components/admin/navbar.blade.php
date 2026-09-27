@props([
    'title' => 'Admin',
])

<nav class="sticky top-0 z-30 border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div class="flex items-center justify-between px-4 py-3 lg:px-6">
        <div class="flex items-center gap-3">
            <button type="button" @click="sidebarOpen = true" aria-controls="admin-sidebar" class="rounded-base p-2 text-gray-500 hover:bg-gray-100 focus:ring-1 focus:ring-gray-200 focus:outline-none md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <svg class="h-6 w-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 5h16M2 10h16M2 15h16"/>
                </svg>
                <span class="sr-only">Open sidebar</span>
            </button>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Admin area</p>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-ui.theme-toggle />

            <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative">
                <button id="admin-user-menu-trigger" type="button" @click="open = ! open" :aria-expanded="open" class="flex rounded-full bg-gray-800 text-sm focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                    <span class="sr-only">Open user menu</span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 font-semibold text-white">AD</span>
                </button>

                <div x-cloak x-show="open" x-transition.origin.top.right class="absolute end-0 top-full z-50 my-4 w-48 list-none divide-y divide-gray-100 rounded-base bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">Admin User</span>
                        <span class="block truncate text-sm text-gray-500 dark:text-gray-400">admin@example.com</span>
                    </div>
                    <ul class="py-2" aria-labelledby="admin-user-menu-trigger">
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Profile</a></li>
                        <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
