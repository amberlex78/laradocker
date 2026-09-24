@props([
    'title' => 'Dashboard',
    'area' => 'admin',
    'navigation' => [],
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ sidebarOpen: false, darkMode: document.documentElement.classList.contains('dark') }"
    x-init="$watch('darkMode', value => { document.documentElement.classList.toggle('dark', value); localStorage.setItem('theme', value ? 'dark' : 'light'); })"
    :class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }} · {{ config('app.name', 'Laravel') }}</title>
        <script>
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        </script>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-slate-950/40 lg:hidden" aria-hidden="true" @click="sidebarOpen = false"></div>

        <x-admin.sidebar :area="$area" :navigation="$navigation" />

        <div class="flex min-h-screen min-w-0 flex-col lg:pl-72">
            <x-admin.header :area="$area" />

            <main class="flex-1">
                <div class="mx-auto flex w-full max-w-screen-2xl flex-col gap-6 p-4 pb-10 sm:p-6 lg:gap-8 lg:p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
