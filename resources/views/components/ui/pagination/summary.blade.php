@props([
    'paginator' => null,
    'from' => 0,
    'to' => 0,
    'total' => 0,
    'label' => 'Entries',
])

@if ($paginator)
    @php
        $from = $paginator->firstItem() ?? 0;
        $to = $paginator->lastItem() ?? 0;
        $total = $paginator->total();
    @endphp
@endif

<p {{ $attributes->class(['text-sm text-gray-500 dark:text-gray-400']) }}>
    Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $from }}-{{ $to }}</span>
    of <span class="font-semibold text-gray-900 dark:text-white">{{ $total }}</span> {{ $label }}
</p>
