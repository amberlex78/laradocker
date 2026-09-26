@php
    $navigation = [
        ['label' => 'Overview', 'route' => 'developer.dashboard', 'icon' => 'layout-dashboard'],
        ['label' => 'Users', 'route' => 'developer.users.index', 'icon' => 'users'],
        ['label' => 'Admin area', 'route' => 'admin.dashboard', 'icon' => 'shield-check'],
    ];
@endphp

<x-layouts.backoffice :title="$title ?? 'Developer dashboard'" area="developer" :navigation="$navigation">
    {{ $slot }}
</x-layouts.backoffice>
