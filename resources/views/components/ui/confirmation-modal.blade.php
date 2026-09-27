@props([
    'id',
    'title',
    'action',
    'method' => 'DELETE',
    'confirmLabel' => "Yes, I'm sure",
    'cancelLabel' => 'No, cancel',
])

<div
    id="{{ $id }}"
    x-data="{ open: false }"
    @open-modal.window="if ($event.detail === '{{ $id }}') open = true"
    @close-modal.window="if ($event.detail === '{{ $id }}') open = false"
    @keydown.escape.window="open = false"
    x-cloak
    x-show="open"
    x-transition.opacity
    @click.self="$dispatch('close-modal', '{{ $id }}')"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    class="fixed inset-0 z-50 flex h-full max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 p-4 dark:bg-gray-900/80"
>
    <div class="relative w-full max-w-md p-4 md:p-6">
        <div class="relative rounded-base border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
            <button type="button" @click="$dispatch('close-modal', '{{ $id }}')" class="absolute end-2.5 top-3 inline-flex h-9 w-9 items-center justify-center rounded-base bg-transparent text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" aria-label="Close modal">
                <x-icon name="x" class="h-5 w-5" />
            </button>

            <div class="p-4 text-center md:p-5">
                <x-icon name="circle-alert" class="mx-auto mb-4 h-12 w-12 text-gray-500 dark:text-gray-400" />
                <h3 id="{{ $id }}-title" class="mb-2 text-lg font-medium leading-relaxed text-gray-900 dark:text-white">{{ $title }}</h3>
                <p class="mb-6 text-sm leading-relaxed text-gray-500 dark:text-gray-400">{{ $slot }}</p>

                <div class="flex items-center justify-center gap-3">
                    <form method="POST" action="{{ $action }}">
                        @csrf
                        @if (strtoupper($method) !== 'POST')
                            @method($method)
                        @endif
                        <x-ui.button type="submit" variant="danger">{{ $confirmLabel }}</x-ui.button>
                    </form>
                    <x-ui.button variant="secondary" @click="$dispatch('close-modal', '{{ $id }}')">{{ $cancelLabel }}</x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>
