@props([
    'name',
    'label' => null,
    'strokeWidth' => '1.8',
])

<svg
    {{ $attributes->merge([
        'viewBox' => '0 0 24 24',
        'fill' => 'none',
        'stroke' => 'currentColor',
        'stroke-width' => $strokeWidth,
        'data-icon' => $name,
        'aria-hidden' => $label ? 'false' : 'true',
        'focusable' => 'false',
    ]) }}
    @if ($label) aria-label="{{ $label }}" @endif
>
    @if ($label)
        <title>{{ $label }}</title>
    @endif

    @switch($name)
        @case('activity')
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-4l-3 9L9 3l-3 9H2" />
            @break
        @case('arrow-left')
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m7 7-7-7 7-7" />
            @break
        @case('arrow-right')
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7-7 7 7-7 7" />
            @break
        @case('check')
            <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
            @break
        @case('chevron-down')
            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
            @break
        @case('circle-alert')
            <circle cx="12" cy="12" r="10" />
            <path stroke-linecap="round" d="M12 8v4M12 16h.01" />
            @break
        @case('circle-check')
            <circle cx="12" cy="12" r="10" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
            @break
        @case('clock')
            <circle cx="12" cy="12" r="10" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            @break
        @case('code-2')
            <path stroke-linecap="round" stroke-linejoin="round" d="m18 16 4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16" />
            @break
        @case('eye')
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" />
            <circle cx="12" cy="12" r="2.5" />
            @break
        @case('eye-off')
            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.58 10.58a2 2 0 0 0 2.83 2.83M9.88 5.09A10.6 10.6 0 0 1 12 4.88c6 0 9.5 7.12 9.5 7.12a17.6 17.6 0 0 1-3.09 3.97M6.61 6.61C4.12 8.25 2.5 12 2.5 12a17.6 17.6 0 0 0 4.01 4.71A10.5 10.5 0 0 0 12 19.12c1.02 0 1.98-.15 2.86-.42" />
            @break
        @case('file-pen')
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M16 13l-4 4-1 3 3-1 4-4a1.41 1.41 0 0 0-2-2Z" />
            @break
        @case('layout-dashboard')
            <rect width="7" height="9" x="3" y="3" rx="1" />
            <rect width="7" height="5" x="14" y="3" rx="1" />
            <rect width="7" height="9" x="14" y="12" rx="1" />
            <rect width="7" height="5" x="3" y="16" rx="1" />
            @break
        @case('log-in')
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
            @break
        @case('log-out')
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
            @break
        @case('menu')
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
            @break
        @case('moon')
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
            @break
        @case('pencil')
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 3a2.85 2.85 0 1 1 4 4L7 21l-4 1 1-4Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m15 5 4 4" />
            @break
        @case('plus')
            <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            @break
        @case('save')
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-8H7v8M7 3v5h8" />
            @break
        @case('send')
            <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 2 11 13" />
            @break
        @case('shield-check')
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13c0 5-3.5 7.5-8 9-4.5-1.5-8-4-8-9V5l8-3 8 3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
            @break
        @case('sun')
            <circle cx="12" cy="12" r="4" />
            <path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
            @break
        @case('trash')
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6M10 11v6M14 11v6" />
            @break
        @case('user-plus')
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M8.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6" />
            @break
        @case('user-round')
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="10" r="3" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20.66V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.66" />
            @break
        @case('users')
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
            @break
        @case('x')
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
            @break
        @default
            @php
                throw new \InvalidArgumentException("Unknown icon [{$name}].");
            @endphp
    @endswitch
</svg>
