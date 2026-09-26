<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Account' }} · {{ config('app.name', 'Laravel') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
        <header class="border-b border-slate-200 bg-white">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4" aria-label="Account navigation">
                <a class="font-semibold tracking-tight" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
                <x-user-menu />
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-10">{{ $slot }}</main>
    </body>
</html>
