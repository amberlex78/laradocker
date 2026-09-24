@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'autocomplete' => null,
    'placeholder' => null,
    'required' => false,
    'autofocus' => false,
])

@php
    $fieldId = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<label class="flex flex-col gap-2" for="{{ $fieldId }}">
    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
        {{ $label }}
        @if ($required)
            <span class="text-red-500" aria-hidden="true">*</span>
        @endif
    </span>

    <input
        {{ $attributes->merge([
            'id' => $fieldId,
            'name' => $name,
            'type' => $type,
            'value' => old($name, $value),
            'autocomplete' => $autocomplete,
            'placeholder' => $placeholder,
            'required' => $required,
            'autofocus' => $autofocus,
            'aria-invalid' => $hasError ? 'true' : 'false',
            'aria-describedby' => $hasError ? $fieldId.'-error' : null,
            'class' => 'h-11 w-full rounded-xl border bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-600 '.($hasError ? 'border-red-300 focus:border-red-500 focus:ring-red-500/10 dark:border-red-500/50' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/10 dark:border-slate-700 dark:focus:border-indigo-500'),
        ]) }}
    >

    @error($name)
        <p id="{{ $fieldId }}-error" class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</label>
