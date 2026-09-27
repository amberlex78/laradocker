@props([
    'title' => 'Account',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-default-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light dark">
        <title>{{ $title }} · {{ config('app.name', 'Laravel') }}</title>

        <x-ui.theme-init />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-white">
        <x-site.navbar :show-user-menu="true" />

        <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">{{ $slot }}</main>

        <x-site.footer />
    </body>
</html>
