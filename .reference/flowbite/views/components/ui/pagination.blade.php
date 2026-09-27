@props([
    'paginator' => null,
    'variant' => 'numbered',
    'currentPage' => 1,
    'lastPage' => 1,
    'pageUrls' => [],
    'pageItems' => [],
    'previousUrl' => null,
    'nextUrl' => null,
    'previousLabel' => 'Previous',
    'nextLabel' => 'Next',
])

@php
    if ($paginator) {
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $pageUrls = $paginator->getUrlRange(1, $lastPage);
        $previousUrl = $paginator->previousPageUrl();
        $nextUrl = $paginator->nextPageUrl();
    } else {
        $previousUrl = $previousUrl ?? ($currentPage > 1 ? ($pageUrls[$currentPage - 1] ?? null) : null);
        $nextUrl = $nextUrl ?? ($currentPage < $lastPage ? ($pageUrls[$currentPage + 1] ?? null) : null);
    }

    $currentPage = max(1, (int) $currentPage);
    $lastPage = max(1, (int) $lastPage);
    $isCompact = $variant === 'compact';

    if ($variant !== 'simple' && $pageItems === []) {
        if ($lastPage <= 7) {
            $pageItems = range(1, $lastPage);
        } else {
            $visiblePages = array_values(array_unique(array_merge(
                [1, 2],
                range(max(1, $currentPage - 1), min($lastPage, $currentPage + 1)),
                [$lastPage - 1, $lastPage],
            )));

            sort($visiblePages);

            $lastVisiblePage = null;

            foreach ($visiblePages as $page) {
                if ($lastVisiblePage !== null && $page - $lastVisiblePage > 1) {
                    $pageItems[] = '...';
                }

                $pageItems[] = $page;
                $lastVisiblePage = $page;
            }
        }
    }
@endphp

<nav {{ $attributes->merge(['aria-label' => 'Pagination']) }}>
    @if ($variant === 'simple')
        <div class="inline-flex -space-x-px rounded-base shadow-xs">
            @if ($previousUrl)
                <a href="{{ $previousUrl }}" class="inline-flex items-center justify-center rounded-s-base border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    {{ $previousLabel }}
                </a>
            @else
                <span aria-disabled="true" class="inline-flex items-center justify-center rounded-s-base border border-gray-300 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-500">
                    {{ $previousLabel }}
                </span>
            @endif

            @if ($nextUrl)
                <a href="{{ $nextUrl }}" class="inline-flex items-center justify-center rounded-e-base border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    {{ $nextLabel }}
                </a>
            @else
                <span aria-disabled="true" class="inline-flex items-center justify-center rounded-e-base border border-gray-300 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-500">
                    {{ $nextLabel }}
                </span>
            @endif
        </div>
    @else
        <ul class="inline-flex -space-x-px text-sm">
            <li>
                @if ($previousUrl)
                    <a href="{{ $previousUrl }}" class="inline-flex h-9 items-center justify-center rounded-s-base border border-gray-300 bg-white px-3 font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        @if (! $isCompact)
                            {{ $previousLabel }}
                        @else
                            <span class="sr-only">{{ $previousLabel }}</span>
                            <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                        @endif
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex h-9 items-center justify-center rounded-s-base border border-gray-300 bg-gray-100 px-3 font-medium text-gray-400 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-500">
                        @if (! $isCompact)
                            {{ $previousLabel }}
                        @else
                            <span class="sr-only">{{ $previousLabel }}</span>
                            <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                        @endif
                    </span>
                @endif
            </li>

            @foreach ($pageItems as $page)
                <li>
                    @if ($page === '...')
                        <span aria-hidden="true" class="inline-flex h-9 w-9 items-center justify-center border border-gray-300 bg-white text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">...</span>
                    @else
                        <a
                            href="{{ $pageUrls[$page] ?? '#' }}"
                            @class([
                                'inline-flex h-9 w-9 items-center justify-center border border-gray-300 font-medium dark:border-gray-700',
                                'bg-blue-50 text-blue-600 dark:bg-gray-700 dark:text-white' => $page === $currentPage,
                                'bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white' => $page !== $currentPage,
                            ])
                            @if ($page === $currentPage) aria-current="page" @endif
                        >
                            {{ $page }}
                        </a>
                    @endif
                </li>
            @endforeach

            <li>
                @if ($nextUrl)
                    <a href="{{ $nextUrl }}" class="inline-flex h-9 items-center justify-center rounded-e-base border border-gray-300 bg-white px-3 font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        @if (! $isCompact)
                            {{ $nextLabel }}
                        @else
                            <span class="sr-only">{{ $nextLabel }}</span>
                            <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        @endif
                    </a>
                @else
                    <span aria-disabled="true" class="inline-flex h-9 items-center justify-center rounded-e-base border border-gray-300 bg-gray-100 px-3 font-medium text-gray-400 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-500">
                        @if (! $isCompact)
                            {{ $nextLabel }}
                        @else
                            <span class="sr-only">{{ $nextLabel }}</span>
                            <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        @endif
                    </span>
                @endif
            </li>
        </ul>
    @endif
</nav>
