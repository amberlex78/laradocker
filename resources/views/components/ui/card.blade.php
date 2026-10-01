@props([
    'title' => null,
    'description' => null,
])

<section {{ $attributes->class(['flex flex-col gap-6 rounded-base border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    @if ($title || $description || isset($actions))
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0 flex-1">
                @if ($title)
                    <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h2>
                @endif

                @if ($description)
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
                @endif
            </div>

            @if (isset($actions))
                <div class="shrink-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</section>
