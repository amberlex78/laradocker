@props(['role'])

@php
    $roleClasses = match ($role->value) {
        'developer' => 'bg-purple-50 text-purple-700 dark:bg-purple-500/15 dark:text-purple-300',
        'admin' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
        'operator' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
    };
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $roleClasses }}">
    {{ str($role->value)->headline() }}
</span>
