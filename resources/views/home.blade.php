<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-default-theme="system">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light dark">

        <title>{{ config('app.name', 'LaFilament') }} — Docker-ready Laravel &amp; Filament starter</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <script data-theme-init>
            (() => {
                const applyTheme = () => {
                    const savedTheme =
                        localStorage.getItem('filament:theme:mode') ??
                        localStorage.getItem('color-theme') ??
                        localStorage.getItem('theme');
                    const defaultTheme = document.documentElement.dataset.defaultTheme ?? 'system';
                    const effectiveTheme = savedTheme ?? defaultTheme;
                    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const shouldUseDark =
                        effectiveTheme === 'dark' ||
                        (effectiveTheme === 'system' && systemPrefersDark);

                    document.documentElement.classList.toggle('dark', shouldUseDark);
                };

                applyTheme();

                window.toggleSiteTheme = () => {
                    const isDark = document.documentElement.classList.contains('dark');
                    const nextTheme = isDark ? 'light' : 'dark';

                    localStorage.setItem('filament:theme:mode', nextTheme);
                    localStorage.setItem('theme', nextTheme);
                    localStorage.setItem('color-theme', nextTheme);
                    document.documentElement.classList.toggle('dark', nextTheme === 'dark');
                };

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-950 antialiased selection:bg-green-600 selection:text-white dark:bg-gray-950 dark:text-white">
        @php
            $appName = config('app.name', 'LaFilament');
            $appInitial = mb_strtoupper(mb_substr($appName, 0, 1));
            $repositoryUrl = 'https://github.com/amberlex78/laradocker';
        @endphp

        @auth
            @php
                $user = auth()->user();

                $ctaDestination = match ($user->role) {
                    \App\Enums\UserRole::Admin => [
                        'label' => 'Admin panel',
                        'url' => filament()->getPanel('admin')->getUrl(),
                    ],
                    \App\Enums\UserRole::Operator => [
                        'label' => 'Operator panel',
                        'url' => filament()->getPanel('operator')->getUrl(),
                    ],
                    default => null,
                };
            @endphp
        @endauth

        <header class="border-b border-gray-200 bg-white/90 backdrop-blur-sm dark:border-white/10 dark:bg-gray-900/90">
            <nav class="mx-auto flex max-w-7xl min-w-0 flex-wrap items-center justify-between gap-2 px-4 py-4 sm:gap-4 sm:px-6 lg:px-8" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="flex min-w-48 flex-1 items-center gap-2.5 sm:gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-linear-to-br from-green-500 to-green-700 text-lg font-bold text-white shadow-sm shadow-green-600/20">
                        {{ $appInitial }}
                    </span>
                    <span class="truncate text-lg font-semibold tracking-tight text-gray-950 sm:text-xl dark:text-white">
                        {{ $appName }}
                    </span>
                </a>

                <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                    <button
                        type="button"
                        onclick="window.toggleSiteTheme()"
                        aria-label="Toggle theme"
                        title="Toggle theme"
                        class="cursor-pointer rounded-lg p-2.5 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:ring-green-500"
                    >
                        <x-filament::icon
                            icon="heroicon-o-moon"
                            class="size-5 block dark:hidden"
                        />
                        <x-filament::icon
                            icon="heroicon-o-sun"
                            class="size-5 hidden dark:block"
                        />
                    </button>

                    @auth
                        <a
                            href="{{ filament()->getPanel('site')->getProfileUrl() }}"
                            class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-lg px-2.5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 hover:text-gray-950 dark:text-gray-200 dark:hover:bg-white/5 dark:hover:text-white sm:px-3"
                        >
                            <x-filament::icon icon="heroicon-o-user-circle" class="size-4 text-green-600 dark:text-green-400" />
                            <span class="hidden sm:inline">{{ $user->name }}</span>
                        </a>

                        <form method="POST" action="{{ route('filament.site.auth.logout') }}" class="inline">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center justify-center gap-1.5 whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 shadow-2xs transition hover:border-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:border-white/15 dark:bg-gray-800 dark:text-gray-200 dark:hover:border-red-500 dark:hover:text-red-400 dark:focus:ring-offset-gray-950"
                            >
                                <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" class="size-4" />
                                <span>Sign out</span>
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('filament.site.auth.login') }}"
                            class="inline-flex w-fit shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-green-600 px-3.5 py-2 text-sm font-medium text-white shadow-xs transition hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500 dark:focus:ring-offset-gray-950"
                        >
                            <x-filament::icon icon="heroicon-o-arrow-right-end-on-rectangle" class="size-4" />
                            <span>Sign in</span>
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main>
            <section class="relative isolate overflow-hidden bg-gray-50 dark:bg-gray-950">
                <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8 lg:py-24">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/15 dark:text-green-300 dark:ring-green-400/30">
                            Laravel &amp;&nbsp;Filament starter kit
                        </span>

                        <h1 class="mt-6 max-w-3xl text-balance text-4xl font-bold tracking-tight text-gray-950 sm:text-5xl lg:text-6xl dark:text-white">
                            A&nbsp;Docker-ready Laravel foundation with Filament workspaces.
                        </h1>

                        <p class="mt-6 max-w-2xl text-pretty text-lg leading-8 text-gray-600 dark:text-gray-300">
                            Clone a&nbsp;clean Laravel&nbsp;13 base, develop in isolated containers, deploy the same foundation to production, and manage users, roles, and operations through ready-made Filament panels.
                        </p>

                        <div class="mt-8 flex flex-wrap items-center gap-3">
                            @auth
                                <a
                                    href="{{ filament()->getPanel('site')->getProfileUrl() }}"
                                    class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm shadow-green-600/20 transition hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500 dark:focus:ring-offset-gray-950"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-right" class="size-4" />
                                    <span>My profile</span>
                                </a>
                            @else
                                <a
                                    href="{{ route('filament.site.auth.login') }}"
                                    class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm shadow-green-600/20 transition hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 dark:bg-green-600 dark:hover:bg-green-500 dark:focus:ring-green-500 dark:focus:ring-offset-gray-950"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-right-end-on-rectangle" class="size-4" />
                                    <span>Sign in</span>
                                </a>

                                <a
                                    href="{{ $repositoryUrl }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 shadow-2xs transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:border-white/15 dark:bg-gray-900 dark:text-white dark:hover:bg-gray-800 dark:focus:ring-white/20"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-1.5 6-5.5a4.7 4.7 0 0 0-1-3.5 4.3 4.3 0 0 0-.1-3.5s-1.1-.3-3.5 1.3a12.3 12.3 0 0 0-6.4 0C6.6 1.7 5.5 2 5.5 2a4.3 4.3 0 0 0-.1 3.5 4.7 4.7 0 0 0-1 3.5c0 4 3 5.5 6 5.5a4.8 4.8 0 0 0-1 3.5v4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 18c-4.51 2-5-2-7-2" />
                                    </svg>
                                    <span>View repository</span>
                                </a>
                            @endauth
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center gap-2">
                                <span class="size-2 rounded-full bg-green-500"></span>
                                Development and production ready
                            </span>
                            <span>No host PHP, Node, or MariaDB required</span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="pointer-events-none absolute -inset-4 rounded-3xl bg-linear-to-tr from-green-200/70 via-amber-100/50 to-blue-200/60 blur-2xl dark:from-green-900/30 dark:via-amber-900/15 dark:to-blue-900/25"></div>
                        <section class="relative flex flex-col gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Start in Docker</h2>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        The host only needs Docker, Compose, and Make.
                                    </p>
                                </div>
                                <span class="w-fit shrink-0 whitespace-nowrap rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-500/15 dark:text-amber-300">
                                    PHP&nbsp;8.5 +&nbsp;Laravel&nbsp;13
                                </span>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-gray-200/80 bg-gray-100 shadow-inner dark:border-white/10 dark:bg-gray-950">
                                <div class="flex items-center gap-2 border-b border-gray-200 px-4 py-3 text-xs text-gray-500 dark:border-white/10 dark:text-gray-400">
                                    <span class="size-2.5 rounded-full bg-red-400"></span>
                                    <span class="size-2.5 rounded-full bg-amber-400"></span>
                                    <span class="size-2.5 rounded-full bg-green-400"></span>
                                    <span class="ml-2 font-mono">terminal</span>
                                </div>
                                <pre class="overflow-x-auto p-5 text-sm leading-7 text-gray-800 dark:text-gray-200"><code><span class="text-green-600 dark:text-green-400">$</span> cp .env.example .env
<span class="text-green-600 dark:text-green-400">$</span> make install
<span class="text-green-600 dark:text-green-400">$</span> make up
<span class="text-green-600 dark:text-green-400">$</span> make migrate</code></pre>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-xl border border-gray-200/60 bg-gray-50 p-4 dark:border-white/5 dark:bg-gray-800/60">
                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-950 dark:text-white">
                                        <x-filament::icon icon="heroicon-o-code-bracket" class="size-4 text-blue-600 dark:text-blue-400" />
                                        <span>Development</span>
                                    </div>
                                    <p class="mt-2 text-pretty text-sm text-gray-500 dark:text-gray-400">
                                        Live source mounts, Adminer, and Vite&nbsp;HMR.
                                    </p>
                                </div>

                                <div class="rounded-xl border border-gray-200/60 bg-gray-50 p-4 dark:border-white/5 dark:bg-gray-800/60">
                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-950 dark:text-white">
                                        <x-filament::icon icon="heroicon-o-shield-check" class="size-4 text-green-600 dark:text-green-400" />
                                        <span>Production</span>
                                    </div>
                                    <p class="mt-2 text-pretty text-sm text-gray-500 dark:text-gray-400">
                                        Self-contained images and isolated services.
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </section>

            <section class="border-y border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900/60">
                <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-8 lg:py-20">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/30">
                            Admin &amp;&nbsp;UI foundation
                        </span>
                        <h2 class="mt-4 text-balance text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                            Filament&nbsp;5 +&nbsp;Livewire&nbsp;4 +&nbsp;Tailwind&nbsp;CSS&nbsp;4
                        </h2>
                        <p class="mt-4 text-pretty text-lg leading-8 text-gray-600 dark:text-gray-300">
                            Build from a&nbsp;cohesive multi-panel Filament architecture instead of assembling auth, tables, forms, and role dashboards from scratch. Each workspace shares login and profile management while keeping permissions cleanly separated.
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-2xs dark:border-white/10 dark:bg-gray-900">
                            <x-filament::icon icon="heroicon-o-squares-2x2" class="size-6 text-amber-600 dark:text-amber-400" />
                            <h3 class="mt-4 text-balance font-semibold text-gray-950 dark:text-white">Filament&nbsp;5 panels</h3>
                            <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                Dedicated Admin, Operator, and Site panels with role-based routing.
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-2xs dark:border-white/10 dark:bg-gray-900">
                            <x-filament::icon icon="heroicon-o-bolt" class="size-6 text-blue-600 dark:text-blue-400" />
                            <h3 class="mt-4 text-balance font-semibold text-gray-950 dark:text-white">Livewire&nbsp;4 &amp;&nbsp;Blaze</h3>
                            <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                Reactive server-driven schemas, tables, and active session controls.
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-2xs dark:border-white/10 dark:bg-gray-900">
                            <x-filament::icon icon="heroicon-o-paint-brush" class="size-6 text-green-600 dark:text-green-400" />
                            <h3 class="mt-4 text-balance font-semibold text-gray-950 dark:text-white">Tailwind&nbsp;CSS&nbsp;4</h3>
                            <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                Utility-first styling with CSS-first configuration and dark mode.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="border-b border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900/40">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/15 dark:text-green-300 dark:ring-green-400/30">
                            Ready to extend
                        </span>
                        <h2 class="mt-4 text-balance text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                            A&nbsp;small foundation, with the important pieces already in&nbsp;place.
                        </h2>
                        <p class="mt-4 text-pretty text-lg leading-8 text-gray-600 dark:text-gray-300">
                            Keep the infrastructure and application basics, then spend your time building the project that belongs on top of&nbsp;them.
                        </p>
                    </div>

                    <div class="mt-10 grid gap-8 md:grid-cols-3">
                        <section class="flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-balance text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                                    Docker-first development
                                </h3>
                                <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                    Run PHP&nbsp;8.5, Composer, Node, Vite, and MariaDB in containers so the host machine stays clean.
                                </p>
                            </div>

                            <span class="w-fit rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-500/15 dark:text-blue-300">
                                No local runtime required
                            </span>
                        </section>

                        <section class="flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-balance text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                                    Development and production
                                </h3>
                                <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                    Use separate Compose configurations for local work and self-contained production images.
                                </p>
                            </div>

                            <span class="w-fit rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-500/15 dark:text-green-300">
                                Built to deploy
                            </span>
                        </section>

                        <section class="flex flex-col justify-between gap-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
                            <div class="min-w-0 flex-1">
                                <h3 class="text-balance text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                                    Authentication and roles
                                </h3>
                                <p class="mt-2 text-pretty text-sm leading-6 text-gray-500 dark:text-gray-400">
                                    Start with users, operators, admins, and a&nbsp;developer role for technical tools and device-aware session security.
                                </p>
                            </div>

                            <span class="w-fit rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-500/15 dark:text-amber-300">
                                Ready for your rules
                            </span>
                        </section>
                    </div>
                </div>
            </section>

            <section class="bg-gray-50 dark:bg-gray-950">
                <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                    <div class="rounded-2xl bg-linear-to-br from-green-600 via-green-700 to-emerald-800 px-6 py-12 text-center shadow-lg shadow-green-900/15 sm:px-12 dark:from-green-600 dark:via-green-700 dark:to-emerald-900">
                        <h2 class="text-balance text-3xl font-bold tracking-tight text-white">
                            Clone the foundation. Build your own project.
                        </h2>
                        <p class="mx-auto mt-4 max-w-2xl text-pretty text-green-100">
                            The infrastructure and Filament panels are ready. The application domain is yours to define, whether you are building a&nbsp;backoffice, an internal portal, a&nbsp;store, or something entirely different.
                        </p>
                        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                            <a
                                href="{{ $repositoryUrl }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-medium text-green-700 shadow-xs transition hover:bg-green-50 focus:outline-none focus:ring-4 focus:ring-green-200"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-1.5 6-5.5a4.7 4.7 0 0 0-1-3.5 4.3 4.3 0 0 0-.1-3.5s-1.1-.3-3.5 1.3a12.3 12.3 0 0 0-6.4 0C6.6 1.7 5.5 2 5.5 2a4.3 4.3 0 0 0-.1 3.5 4.7 4.7 0 0 0-1 3.5c0 4 3 5.5 6 5.5a4.8 4.8 0 0 0-1 3.5v4" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 18c-4.51 2-5-2-7-2" />
                                </svg>
                                <span>View repository</span>
                            </a>

                            @guest
                                <a
                                    href="{{ route('filament.site.auth.login') }}"
                                    class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/30"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-right-end-on-rectangle" class="size-4" />
                                    <span>Sign in</span>
                                </a>
                            @else
                                <a
                                    href="{{ filament()->getPanel('site')->getProfileUrl() }}"
                                    class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/30"
                                >
                                    <x-filament::icon icon="heroicon-o-arrow-right" class="size-4" />
                                    <span>My profile</span>
                                </a>

                                @if ($ctaDestination)
                                    <a
                                        href="{{ $ctaDestination['url'] }}"
                                        class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/30"
                                    >
                                        <x-filament::icon icon="heroicon-o-squares-2x2" class="size-4" />
                                        <span>{{ $ctaDestination['label'] }}</span>
                                    </a>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 py-6 text-sm text-gray-500 sm:px-6 lg:px-8 dark:text-gray-400">
                &copy; {{ now()->year }} {{ $appName }}. All rights reserved.
            </div>
        </footer>
    </body>
</html>
