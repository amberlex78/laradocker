<x-layouts.auth :title="'Forgot password'">
    <div class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-400">Account recovery</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Forgot your password?</h1>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">Enter your email and we will send you a secure reset link.</p>
    </div>

    @if (session('status'))
        <x-auth.alert class="mt-6" title="Check your inbox" type="success">{{ session('status') }}</x-auth.alert>
    @endif

    @if ($errors->any())
        <x-auth.alert class="mt-6" title="We could not send the reset link">
            {{ $errors->first() }}
        </x-auth.alert>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-auth.field name="email" label="Email" type="email" autocomplete="email" placeholder="you@example.com" required autofocus />

        <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20" type="submit">
            <x-icon name="send" class="h-4 w-4" />
            Send reset link
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        Remembered your password?
        <a class="inline-flex items-center gap-1.5 font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" href="{{ route('login') }}"><x-icon name="arrow-left" class="h-4 w-4" />Back to login</a>
    </p>
</x-layouts.auth>
