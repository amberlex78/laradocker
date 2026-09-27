@props([
    'label',
    'href' => '#',
    'active' => false,
])

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'group flex items-center rounded-base p-2 text-base font-medium',
        'bg-gray-700 text-white' => $active,
        'text-gray-300 hover:bg-gray-700 hover:text-white' => ! $active,
    ]) }}
>
    <span class="h-5 w-5 shrink-0 text-gray-400 transition duration-75 group-hover:text-white">
        {{ $slot }}
    </span>
    <span class="ms-3">{{ $label }}</span>
</a>
