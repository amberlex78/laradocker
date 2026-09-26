@props([
    'name',
    'label',
    'errorBag' => 'default',
])

@php
    $fieldId = $attributes->get('id', $name);
    $attributes = $attributes->except('value');
    $isRequired = $attributes->get('required', false) !== false;
    $bag = $errors->getBag($errorBag);
    $hasError = $bag->has($name);
    $errorMessage = $hasError ? $bag->first($name) : null;
@endphp

<label class="flex flex-col gap-2" for="{{ $fieldId }}" x-data="{ visible: false }">
    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
        {{ $label }}
        @if ($isRequired)
            <span class="text-red-500" aria-hidden="true">*</span>
        @endif
    </span>

    <span class="relative">
        <input
            {{ $attributes->merge([
                'id' => $fieldId,
                'name' => $name,
                'type' => 'password',
                'autocomplete' => 'new-password',
                'placeholder' => 'Enter your password',
                'aria-invalid' => $hasError ? 'true' : 'false',
                'aria-describedby' => $hasError ? $fieldId.'-error' : null,
                'data-invalid' => $hasError ? 'true' : 'false',
                'data-validation-field' => 'true',
                'class' => 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 pr-12 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-600 dark:focus:border-indigo-500 data-[invalid=true]:border-red-300 data-[invalid=true]:focus:border-red-500 data-[invalid=true]:focus:ring-red-500/10 data-[invalid=true]:dark:border-red-500/50',
            ]) }}
            x-bind:type="visible ? 'text' : 'password'"
        >

        <button
            type="button"
            class="absolute inset-y-0 right-0 inline-flex w-12 items-center justify-center text-slate-400 transition hover:text-slate-700 dark:text-slate-500 dark:hover:text-slate-200"
            :aria-label="visible ? 'Hide password' : 'Show password'"
            @click="visible = !visible"
        >
            <x-icon name="eye" x-cloak x-show="!visible" class="h-5 w-5" />
            <x-icon name="eye-off" x-cloak x-show="visible" class="h-5 w-5" />
        </button>
    </span>

    <p
        id="{{ $fieldId }}-error"
        class="text-xs text-red-600 dark:text-red-400"
        data-validation-error
        @if (! $hasError)
            hidden
        @endif
    >{{ $errorMessage }}</p>
</label>
