@php
    $navigation = [
        ['label' => 'Overview', 'route' => 'developer.dashboard'],
        ['label' => 'Users', 'route' => 'developer.users.index'],
        ['label' => 'Admin area', 'route' => 'admin.dashboard'],
    ];
@endphp

<x-layouts.backoffice :title="$title ?? 'Developer dashboard'" area="developer" :navigation="$navigation">
    {{ $slot }}
</x-layouts.backoffice>
