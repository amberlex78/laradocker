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
    $inputClasses = 'w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white dark:placeholder:text-slate-500';
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
        <div class="grid gap-2">
            <label for="name" class="{{ $labelClasses }}">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user?->name) }}" class="{{ $inputClasses }}" required autofocus>
            @error('name')
                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="email" class="{{ $labelClasses }}">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}" class="{{ $inputClasses }}" required>
            @error('email')
                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="password" class="{{ $labelClasses }}">
                Password
                @if ($user)
                    <span class="font-normal text-slate-400">(leave blank to keep it)</span>
                @endif
            </label>
            <input id="password" name="password" type="password" class="{{ $inputClasses }}" @required(!$user)>
            @error('password')
                <p class="text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="password_confirmation" class="{{ $labelClasses }}">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="{{ $inputClasses }}" @required(!$user)>
        </div>

        <div class="grid gap-2 sm:col-span-2">
            <label for="role" class="{{ $labelClasses }}">Role</label>

            @if (! $roleEditable)
                <input type="hidden" name="role" value="{{ $selectedRole }}">
            @endif

            <div class="relative z-20 bg-transparent">
                <select id="role" name="role" class="{{ $inputClasses }} appearance-none bg-none pr-11" @disabled(! $roleEditable) required>
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
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
            <x-icon name="save" class="h-4 w-4" />
            {{ $submitLabel }}
        </button>
    </div>
</form>
