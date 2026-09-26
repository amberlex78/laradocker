@php
    $navigation = [
        ['label' => 'Overview', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard'],
    ];

    if (auth()->user()->role === \App\Enums\UserRole::Admin) {
        $navigation[] = ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users'];
    }

    if (auth()->user()->role === \App\Enums\UserRole::Developer) {
        $navigation[] = ['label' => 'Developer area', 'route' => 'developer.dashboard', 'icon' => 'code-2'];
    }
@endphp

<x-layouts.backoffice :title="$title ?? 'Admin dashboard'" area="admin" :navigation="$navigation">
    {{ $slot }}
</x-layouts.backoffice>
