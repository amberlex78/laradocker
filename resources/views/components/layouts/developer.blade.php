<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Developer' }} · {{ config('app.name', 'Laravel') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="flex min-h-screen flex-col md:flex-row">
            <aside class="w-full shrink-0 border-b border-slate-800 bg-slate-900 p-6 md:w-72 md:border-b-0 md:border-r">
                <div class="flex flex-col gap-8">
                    <a class="font-semibold tracking-tight" href="{{ route('developer.dashboard') }}">{{ config('app.name', 'Laravel') }} Developer</a>
                    <nav class="flex flex-col gap-2 text-sm" aria-label="Developer navigation">
                        <span class="pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Developer navigation</span>
                        <a class="rounded-lg bg-indigo-500/20 px-3 py-2 text-indigo-200" href="{{ route('developer.dashboard') }}">Dashboard</a>
                        <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="{{ route('admin.dashboard') }}">Admin area</a>
                    </nav>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="flex items-center justify-between border-b border-slate-800 bg-slate-950 px-6 py-4">
                    <span class="text-sm text-slate-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium text-slate-400 hover:text-white" type="submit">Log out</button>
                    </form>
                </header>
                <main class="flex-1 p-6 md:p-10">{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
