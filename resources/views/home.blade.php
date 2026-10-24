<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-950 dark:bg-gray-950 dark:text-white">
        @auth
            @php
                $user = auth()->user();
                $destinations = [];

                if ($user->role->canAccessAdminPanel()) {
                    $destinations[] = [
                        'label' => 'Admin panel',
                        'icon' => 'heroicon-o-squares-2x2',
                        'description' => 'Open the administration dashboard.',
                        'colorClasses' => 'border-amber-200 bg-amber-50 hover:border-amber-500 focus:ring-amber-600 dark:border-amber-800 dark:bg-amber-950/30',
                        'labelClasses' => 'text-amber-400',
                        'url' => filament()->getPanel('admin')->getUrl(),
                    ];
                }

                if ($user->role->canAccessOperatorPanel()) {
                    $destinations[] = [
                        'label' => 'Operator panel',
                        'icon' => 'heroicon-o-squares-2x2',
                        'description' => 'Open the operator workspace.',
                        'colorClasses' => 'border-blue-200 bg-blue-50 hover:border-blue-500 focus:ring-blue-600 dark:border-blue-800 dark:bg-blue-950/30',
                        'labelClasses' => 'text-blue-400',
                        'url' => filament()->getPanel('operator')->getUrl(),
                    ];
                }

                $destinations[] = [
                    'label' => 'My profile',
                    'icon' => 'heroicon-o-user-circle',
                    'description' => 'Update your account and security settings.',
                    'colorClasses' => 'border-green-200 bg-green-50 hover:border-green-500 focus:ring-green-600 dark:border-green-800 dark:bg-green-950/30',
                    'labelClasses' => 'text-green-400',
                    'url' => filament()->getPanel('site')->getProfileUrl(),
                ];
            @endphp
        @endauth

        <main class="flex min-h-screen items-center justify-center px-6 py-16 lg:px-8">
            <div class="w-full max-w-4xl text-center">
                <p class="text-sm font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">
                    {{ config('app.name', 'Laravel') }}
                </p>

                @auth
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">
                        Welcome back, {{ $user->name }}
                    </h1>
                    <p class="mx-auto mt-5 max-w-xl text-lg leading-8 text-gray-600 dark:text-gray-300">
                        Choose the section you want to open.
                    </p>

                    <div class="mx-auto mt-10 flex w-full max-w-md flex-col gap-4 text-left">
                        @foreach ($destinations as $destination)
                            <a
                                href="{{ $destination['url'] }}"
                                class="{{ $destination['colorClasses'] }} cursor-pointer rounded-xl p-6 shadow-sm transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
                            >
                                <div class="flex items-center gap-3">
                                    <x-filament::icon
                                        :icon="$destination['icon']"
                                        class="{{ $destination['labelClasses'] }} size-6 shrink-0"
                                    />
                                    <span class="{{ $destination['labelClasses'] }} text-lg font-semibold">{{ $destination['label'] }}</span>
                                </div>
                                <span class="mt-2 block text-sm leading-6 text-gray-600 dark:text-gray-300">
                                    {{ $destination['description'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('filament.site.auth.logout') }}" class="mx-auto mt-8 w-full max-w-md">
                        @csrf
                        <button
                            type="submit"
                            class="flex min-h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-gray-300 px-5 py-3 font-medium text-gray-700 transition hover:border-red-500 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:border-gray-700 dark:text-gray-200 dark:focus:ring-offset-gray-950"
                        >
                            <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" class="size-5" />
                            <span>Sign out</span>
                        </button>
                    </form>
                @else
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight sm:text-5xl">
                        Welcome
                    </h1>
                    <p class="mx-auto mt-5 max-w-xl text-lg leading-8 text-gray-600 dark:text-gray-300">
                        Sign in to access your available sections.
                    </p>

                    <a
                        href="{{ route('filament.site.auth.login') }}"
                        class="mx-auto mt-10 flex min-h-12 w-full max-w-sm items-center justify-center rounded-lg bg-green-600 px-5 py-3 font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
                    >
                        Sign in
                    </a>
                @endauth
            </div>
        </main>
    </body>
</html>
