<x-layouts.auth :title="'Reset password'">
    <div class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-400">Account recovery</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Reset your password</h1>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">Choose a new password for your {{ config('app.name', 'Laravel') }} account.</p>
    </div>

    @if ($errors->any())
        <x-auth.alert class="mt-6" title="We could not reset your password">
            <ul class="flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-auth.alert>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input name="token" type="hidden" value="{{ $request->route('token') }}">

        <x-auth.field name="email" label="Email" type="email" :value="$request->email" autocomplete="email" placeholder="you@example.com" required autofocus />
        <x-auth.password-field name="password" label="New password" autocomplete="new-password" placeholder="Create a new password" required />
        <x-auth.password-field name="password_confirmation" label="Confirm password" autocomplete="new-password" placeholder="Repeat your new password" required />

        <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20" type="submit">
            Reset password
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        <a class="font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" href="{{ route('login') }}">Back to login</a>
    </p>
</x-layouts.auth>
