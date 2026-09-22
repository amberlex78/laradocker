<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Admin' }} · {{ config('app.name', 'Laravel') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col md:flex-row">
            <aside class="w-full shrink-0 border-b border-slate-800 bg-slate-900 p-6 text-white md:w-64 md:border-b-0 md:border-r">
                <div class="flex flex-col gap-8">
                    <a class="font-semibold tracking-tight" href="{{ route('admin.dashboard') }}">{{ config('app.name', 'Laravel') }} Admin</a>
                    <nav class="flex flex-col gap-2 text-sm" aria-label="Admin navigation">
                        <span class="pb-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Admin navigation</span>
                        <a class="rounded-lg bg-white/10 px-3 py-2 text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @if (auth()->user()->role === \App\Enums\UserRole::Developer)
                            <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="{{ route('developer.dashboard') }}">Technical area</a>
                        @endif
                    </nav>
                </div>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="flex items-center justify-between border-b border-slate-200 bg-white px-6 py-4">
                    <span class="text-sm text-slate-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm font-medium text-slate-600 hover:text-slate-950" type="submit">Log out</button>
                    </form>
                </header>
                <main class="flex-1 p-6 md:p-10">{{ $slot }}</main>
            </div>
        </div>
    </body>
</html>
