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
@endphp

<form method="POST" action="{{ $action }}" class="grid gap-6">
    @csrf

    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <x-ui.alert variant="danger" title="Please review the highlighted fields." />
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <x-ui.input
            name="name"
            label="Full name"
            :value="old('name', $user?->name)"
            :error="$errors->first('name')"
            required
            autofocus
        />

        <x-ui.input
            name="email"
            label="Email address"
            type="email"
            :value="old('email', $user?->email)"
            :error="$errors->first('email')"
            required
        />

        <x-ui.input
            name="password"
            label="Password"
            type="password"
            :error="$errors->first('password')"
            :required="$user === null"
        />

        <x-ui.input
            name="password_confirmation"
            label="Confirm password"
            type="password"
            :error="$errors->first('password_confirmation')"
            :required="$user === null"
        />

        @if (! $roleEditable)
            <input type="hidden" name="role" value="{{ $selectedRole }}">
        @endif

        <x-ui.select
            name="role"
            label="Role"
            hint="{{ $roleEditable ? '' : 'This role is protected in the current workspace.' }}"
            :error="$errors->first('role')"
            :disabled="! $roleEditable"
            :required="true"
            class="sm:col-span-2"
        >
            @foreach ($availableRoles as $role)
                <option value="{{ $role->value }}" @selected($selectedRole === $role->value)>{{ str($role->value)->headline() }}</option>
            @endforeach
        </x-ui.select>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-200 pt-5 dark:border-gray-700">
        @if ($cancelUrl)
            <x-ui.link-button href="{{ $cancelUrl }}" variant="secondary">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back to users
            </x-ui.link-button>
        @endif
        <x-ui.button type="submit">
            <x-icon name="save" class="h-4 w-4" />
            {{ $submitLabel }}
        </x-ui.button>
    </div>
</form>
