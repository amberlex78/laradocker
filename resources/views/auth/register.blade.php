<x-layouts.auth :title="'Register'">
    <div class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-400">Get started</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Create your account</h1>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">Create a regular {{ config('app.name', 'Laravel') }} account to get started.</p>
    </div>

    @if ($errors->any())
        <x-auth.alert class="mt-6" title="Please check the form">
            <ul class="flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-auth.alert>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('register') }}">
        @csrf

        <x-auth.field name="name" label="Name" autocomplete="name" placeholder="Your name" required autofocus />
        <x-auth.field name="email" label="Email" type="email" autocomplete="email" placeholder="you@example.com" required />
        <x-auth.password-field name="password" label="Password" autocomplete="new-password" placeholder="Create a password" required />
        <x-auth.password-field name="password_confirmation" label="Confirm password" autocomplete="new-password" placeholder="Repeat your password" required />

        <button class="mt-1 inline-flex h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20" type="submit">
            <x-icon name="user-plus" class="h-4 w-4" />
            Create account
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        Already have an account?
        <a class="inline-flex items-center gap-1.5 font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" href="{{ route('login') }}">Log in<x-icon name="arrow-right" class="h-4 w-4" /></a>
    </p>
</x-layouts.auth>
