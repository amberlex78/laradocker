@props(['title' => 'Authentication'])

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
    <body class="min-h-screen bg-white text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <div class="min-h-screen lg:grid lg:grid-cols-2">
            <section class="flex min-h-screen flex-col">
                <div class="mx-auto flex min-h-screen w-full max-w-xl flex-1 flex-col px-6 py-8 sm:px-10 lg:px-16 xl:px-24">
                    <div class="flex items-center justify-between gap-4">
                        <a class="inline-flex items-center gap-3 text-sm font-semibold tracking-tight text-slate-950 dark:text-white" href="{{ route('home') }}">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white shadow-lg shadow-indigo-600/20">L</span>
                            {{ config('app.name', 'Laravel') }}
                        </a>

                        <div class="flex items-center gap-3">
                            <a class="hidden items-center gap-1.5 text-sm font-medium text-slate-500 transition hover:text-slate-950 sm:inline-flex dark:text-slate-400 dark:hover:text-white" href="{{ route('home') }}"><x-icon name="arrow-left" class="h-4 w-4" />Back to LaDocker</a>

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
                        </div>
                    </div>

                    <div class="flex flex-1 items-center py-12">
                        <div class="mx-auto w-full max-w-md">
                            {{ $slot }}
                        </div>
                    </div>

                    <p class="text-center text-xs text-slate-400 dark:text-slate-500">
                        Secure access to {{ config('app.name', 'Laravel') }}
                    </p>
                </div>
            </section>

            <aside class="relative hidden overflow-hidden bg-slate-950 lg:flex" aria-label="Authentication branding">
                <div class="absolute inset-0 opacity-40" aria-hidden="true" style="background-image: linear-gradient(rgba(148, 163, 184, 0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(148, 163, 184, 0.12) 1px, transparent 1px); background-size: 48px 48px;"></div>
                <div class="absolute -right-32 -top-32 h-96 w-96 rounded-full bg-indigo-600/30 blur-3xl" aria-hidden="true"></div>
                <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl" aria-hidden="true"></div>

                <div class="relative flex w-full items-center justify-center p-12 xl:p-20">
                    <div class="max-w-lg">
                        <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-500 text-xl font-bold text-white shadow-2xl shadow-indigo-500/30">L</div>
                        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.24em] text-indigo-300">{{ config('app.name', 'Laravel') }}</p>
                        <h2 class="text-4xl font-semibold tracking-tight text-white xl:text-5xl">One workspace for your applications.</h2>
                        <p class="mt-6 max-w-md text-lg leading-8 text-slate-300">Manage your account, business operations, and developer tools from one place.</p>

                        <div class="mt-10 flex flex-wrap gap-3 text-sm text-slate-300">
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Account</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Administration</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2">Developer tools</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </body>
</html>
