@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'error' => null,
])

<div x-data="{ passwordVisible: false }">
    <x-ui.input
        :label="$label"
        :name="$name"
        :hint="$hint"
        :error="$error"
        type="password"
        x-bind:type="passwordVisible ? 'text' : 'password'"
        {{ $attributes->class(['pe-11']) }}
    >
        <x-slot:trailing>
            <button
                type="button"
                class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-500 transition-colors hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 dark:text-gray-400 dark:hover:text-white dark:focus-visible:ring-blue-500"
                @click="passwordVisible = ! passwordVisible"`
                @pointerup="$event.currentTarget.blur()"
                :aria-label="passwordVisible ? 'Hide password' : 'Show password'"
                :aria-pressed="passwordVisible"
            >
                <x-icon name="eye" x-cloak x-show="! passwordVisible" class="h-5 w-5" />
                <x-icon name="eye-off" x-cloak x-show="passwordVisible" class="h-5 w-5" />
            </button>
        </x-slot:trailing>
    </x-ui.input>
</div>
