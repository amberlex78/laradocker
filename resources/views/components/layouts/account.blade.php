<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: document.documentElement.classList.contains('dark') }"
    x-init="$watch('darkMode', value => { document.documentElement.classList.toggle('dark', value); localStorage.setItem('theme', value ? 'dark' : 'light'); })"
    :class="{ 'dark': darkMode }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Account' }} · {{ config('app.name', 'Laravel') }}</title>
        <script>
            document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark');
        </script>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-950">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4" aria-label="Account navigation">
                <a class="font-semibold tracking-tight text-slate-950 dark:text-white" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-100 hover:text-slate-950 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-white"
                        aria-label="Toggle theme"
                        title="Toggle theme"
                        :aria-pressed="darkMode"
                        @click="darkMode = !darkMode"
                    >
                        <x-icon name="sun" x-cloak x-show="!darkMode" class="h-5 w-5" />
                        <x-icon name="moon" x-cloak x-show="darkMode" class="h-5 w-5" />
                    </button>

                    <x-user-menu />
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10">{{ $slot }}</main>
    </body>
</html>
