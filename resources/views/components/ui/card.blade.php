@props([
    'title' => null,
    'description' => null,
])

<section {{ $attributes->class(['rounded-base border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    @if (isset($actions))
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                @if ($title)
                    <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h2>
                @endif

                @if ($description)
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>

            <div class="shrink-0">
                {{ $actions }}
            </div>
        </div>
    @else
        @if ($title)
            <h2 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h2>
        @endif

        @if ($description)
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
        @endif
    @endif

    {{ $slot }}
</section>
