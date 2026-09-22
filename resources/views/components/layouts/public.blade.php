<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-950 text-white antialiased">
        <header class="border-b border-white/10">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5" aria-label="Public navigation">
                <a class="text-lg font-semibold tracking-tight" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
                <div class="flex items-center gap-4 text-sm text-slate-300">
                    @auth
                        <a class="hover:text-white" href="{{ route('account') }}">Account</a>
                    @else
                        <a class="hover:text-white" href="{{ route('login') }}">Log in</a>
                        <a class="rounded-lg bg-white px-4 py-2 font-semibold text-slate-950 hover:bg-slate-200" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main>{{ $slot }}</main>
    </body>
</html>
