<aside id="admin-sidebar" class="fixed start-0 top-0 z-40 h-screen w-64 -translate-x-full border-e border-gray-700 bg-gray-800 transition-transform md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }" aria-label="Admin sidebar">
    <div class="flex h-full flex-col overflow-y-auto px-3 py-4">
        <div class="mb-6 flex items-center justify-between px-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-base bg-blue-600 text-lg font-bold text-white">A</span>
                <span class="self-center whitespace-nowrap text-xl font-semibold text-white">Admin</span>
            </a>

            <button type="button" @click="sidebarOpen = false" aria-controls="admin-sidebar" class="rounded-base p-2 text-gray-400 hover:bg-gray-700 hover:text-white md:hidden">
                <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close sidebar</span>
            </button>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <x-admin.nav-item label="Dashboard" :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 8.039 8.039A1 1 0 0 0 16.975 11Z"/>
                        <path d="M12.5 0a1 1 0 0 0-1 1v8.5a1 1 0 0 0 1 1H21a1 1 0 0 0 1.066-.998A8.5 8.5 0 0 0 12.5 0Z"/>
                    </svg>
                </x-admin.nav-item>
            </li>
            <li>
                <x-admin.nav-item label="Users">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                        <path d="M14 2a3.963 3.963 0 0 0-1.9.5A3.978 3.978 0 0 0 8 0a3.978 3.978 0 0 0-4.1 2.5A3.963 3.963 0 0 0 2 2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-2Zm-4 1a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm-2 4a4 4 0 0 1 4 4H4a4 4 0 0 1 4-4Z"/>
                    </svg>
                </x-admin.nav-item>
            </li>
            <li>
                <x-admin.nav-item label="Settings">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.5 9H16V7.5a1.5 1.5 0 0 0-3 0V9h-1.5a1.5 1.5 0 0 0 0 3H13v1.5a1.5 1.5 0 0 0 3 0V12h1.5a1.5 1.5 0 0 0 0-3ZM8.5 1H7V-.5a1.5 1.5 0 0 0-3 0V1H2.5a1.5 1.5 0 0 0 0 3H4v1.5a1.5 1.5 0 0 0 3 0V4h1.5a1.5 1.5 0 0 0 0-3Z"/>
                    </svg>
                </x-admin.nav-item>
            </li>
        </ul>

        <div class="mt-auto border-t border-gray-700 pt-4">
            <a href="{{ route('home') }}" class="group flex items-center rounded-base p-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="h-5 w-5 shrink-0 text-gray-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 9h16M9 1l8 8-8 8"/>
                </svg>
                <span class="ms-3">View public site</span>
            </a>
        </div>
    </div>
</aside>
