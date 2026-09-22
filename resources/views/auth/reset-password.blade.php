<x-layouts.auth :title="'Reset password'">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold tracking-tight">Reset your password</h1>
        <p class="text-sm text-slate-500">Choose a new password for your account.</p>
    </div>

    @if ($errors->any())
        <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input name="token" type="hidden" value="{{ $request->route('token') }}">

        <label class="flex flex-col gap-2 text-sm font-medium" for="email">
            Email
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email">
        </label>

        <label class="flex flex-col gap-2 text-sm font-medium" for="password">
            New password
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password" name="password" type="password" required autocomplete="new-password">
        </label>

        <label class="flex flex-col gap-2 text-sm font-medium" for="password_confirmation">
            Confirm password
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
        </label>

        <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" type="submit">Reset password</button>
    </form>
</x-layouts.auth>
