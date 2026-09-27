@props([
    'title',
    'description' => null,
])

<section {{ $attributes->class(['rounded-base border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    <header>
        <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h2>

        @if ($description)
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    </header>

    {{ $slot }}
</section>
