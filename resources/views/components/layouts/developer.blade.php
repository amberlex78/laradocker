@php
    $navigation = [
        ['label' => 'Overview', 'route' => 'developer.dashboard'],
        ['label' => 'Admin area', 'route' => 'admin.dashboard'],
    ];
@endphp

<x-layouts.backoffice :title="$title ?? 'Developer dashboard'" area="developer" :navigation="$navigation">
    {{ $slot }}
</x-layouts.backoffice>
