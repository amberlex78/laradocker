@props([
    'paginator' => null,
    'currentPage' => 1,
    'lastPage' => 1,
    'from' => 0,
    'to' => 0,
    'total' => 0,
    'pageUrls' => [],
    'pageItems' => [],
    'label' => 'Entries',
])

<x-ui.table.footer {{ $attributes }}>
    <x-ui.pagination.summary :paginator="$paginator" :from="$from" :to="$to" :total="$total" :label="$label" />
    <x-ui.pagination
        :paginator="$paginator"
        :current-page="$currentPage"
        :last-page="$lastPage"
        :page-urls="$pageUrls"
        :page-items="$pageItems !== [] ? $pageItems : array_keys($pageUrls)"
        aria-label="Table pagination"
    />
</x-ui.table.footer>
