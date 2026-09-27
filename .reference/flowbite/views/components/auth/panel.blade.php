@props([
    'title',
    'description' => null,
])

<div class="w-full max-w-md space-y-6">
    <div class="text-center">
        <a href="{{ route('home') }}" class="mb-6 inline-flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-base bg-blue-600 text-lg font-bold text-white">A</span>
            <span class="text-2xl font-semibold text-gray-900 dark:text-white">Application</span>
        </a>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h1>
        @if ($description)
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </div>

    <div {{ $attributes->class(['rounded-base bg-white p-6 shadow-sm sm:p-8 dark:border dark:border-gray-700 dark:bg-gray-800']) }}>
        {{ $slot }}
    </div>
</div>
