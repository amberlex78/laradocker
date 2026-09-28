@props(['role'])

@php
    $variant = match ($role->value) {
        'developer' => 'blue',
        'operator' => 'yellow',
        'admin' => 'green',
        default => 'gray',
    };
@endphp

<x-ui.badge :variant="$variant">
    {{ str($role->value)->headline() }}
</x-ui.badge>
