@props([
    'title' => 'Dashboard',
    'area' => 'admin',
    'navigation' => [],
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-default-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="dark light">
        <title>{{ $title }} · {{ config('app.name', 'Laravel') }}</title>

        <x-ui.theme-init />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-white">
        <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen">
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity
                @click="sidebarOpen = false"
                class="fixed inset-0 z-30 bg-gray-900/50 md:hidden dark:bg-gray-900/80"
                aria-hidden="true"
            ></div>

            <x-admin.sidebar :area="$area" :navigation="$navigation" />

            <div class="min-w-0 md:ms-64">
                <x-admin.header :area="$area" />

                <main class="min-w-0 w-full p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
