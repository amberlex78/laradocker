@php
    $navigation = [
        ['label' => 'Overview', 'route' => 'admin.dashboard'],
    ];

    if (auth()->user()->role === \App\Enums\UserRole::Developer) {
        $navigation[] = ['label' => 'Developer area', 'route' => 'developer.dashboard'];
    }
@endphp

<x-layouts.backoffice :title="$title ?? 'Admin dashboard'" area="admin" :navigation="$navigation">
    {{ $slot }}
</x-layouts.backoffice>
