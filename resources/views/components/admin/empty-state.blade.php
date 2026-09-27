@props([
    'title',
    'description',
])

<div class="flex min-h-36 flex-col items-center justify-center rounded-base border border-dashed border-gray-200 bg-gray-50 px-5 py-8 text-center dark:border-gray-700 dark:bg-gray-700/50">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
</div>
