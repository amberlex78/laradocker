@props([
    'user' => null,
    'action',
    'method' => 'POST',
    'availableRoles' => [],
    'roleEditable' => true,
    'submitLabel' => 'Save changes',
    'cancelUrl' => null,
])

@php
    $selectedRole = old('role', $user?->role?->value ?? \App\Enums\UserRole::User->value);
    $labelClasses = 'text-sm font-medium text-slate-700 dark:text-slate-300';
@endphp

<form method="POST" action="{{ $action }}" class="grid gap-6">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
            <p class="font-semibold">Please review the highlighted fields.</p>
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <x-ui.input
            name="name"
            label="Full name"
            :value="old('name', $user?->name)"
            required
            autofocus
        />

        <x-ui.input
            name="email"
            label="Email address"
            type="email"
            :value="old('email', $user?->email)"
            required
        />

        <x-ui.input
            name="password"
            label="Password"
            type="password"
            :required="$user === null"
        />

        <x-ui.input
            name="password_confirmation"
            label="Confirm password"
            type="password"
            :required="$user === null"
        />

        <div class="grid gap-2 sm:col-span-2">
            <label for="role" class="{{ $labelClasses }}">Role</label>

            @if (! $roleEditable)
                <input type="hidden" name="role" value="{{ $selectedRole }}">
            @endif

            <div class="relative z-20 bg-transparent">
                <select id="role" name="role" class="block w-full appearance-none rounded-base border border-gray-300 bg-gray-50 p-2.5 pr-11 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400" @disabled(! $roleEditable) required>
                    @foreach ($availableRoles as $role)
                        <option value="{{ $role->value }}" @selected($selectedRole === $role->value)>{{ str($role->value)->headline() }}</option>
                    @endforeach
                </select>

                <span class="pointer-events-none absolute inset-y-0 right-3 z-30 flex items-center text-slate-500 dark:text-slate-400">
                    <x-icon name="chevron-down" class="h-5 w-5" />
                </span>
            </div>

            @if (! $roleEditable)
                <p class="text-xs text-slate-500 dark:text-slate-400">This role is protected in the current workspace.</p>
            @endif

            @error('role')
                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5 dark:border-slate-800">
        @if ($cancelUrl)
            <a href="{{ $cancelUrl }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"><x-icon name="arrow-left" class="h-4 w-4" />Cancel</a>
        @endif
        <x-ui.button type="submit">
            <x-icon name="save" class="h-4 w-4" />
            {{ $submitLabel }}
        </x-ui.button>
    </div>
</form>
