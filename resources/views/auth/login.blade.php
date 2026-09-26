<x-layouts.auth :title="'Log in'">
    <div class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-400">Welcome back</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Log in to your account</h1>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">Use your {{ config('app.name', 'Laravel') }} credentials to continue.</p>
    </div>

    @if ($errors->any())
        <x-auth.alert class="mt-6" title="Unable to sign in">
            <ul class="flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-auth.alert>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('login') }}">
        @csrf

        <x-auth.field name="email" label="Email" type="email" autocomplete="email" placeholder="you@example.com" required autofocus />
        <x-auth.password-field name="password" label="Password" autocomplete="current-password" placeholder="Enter your password" required />

        <div class="flex items-center justify-between gap-4">
            <div x-data="{ checkboxToggle: false }">
                <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600 select-none dark:text-slate-400" for="remember">
                    <input class="sr-only" id="remember" name="remember" type="checkbox" x-model="checkboxToggle">
                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-md border-[1.25px] transition"
                        :class="checkboxToggle ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300 bg-transparent dark:border-slate-700'"
                        aria-hidden="true"
                    >
                        <x-icon name="check" class="h-3.5 w-3.5 text-white transition" x-bind:class="checkboxToggle ? 'opacity-100' : 'opacity-0'" />
                    </span>
                    Remember me
                </label>
            </div>
            <a class="text-sm font-medium text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20" type="submit">
            <x-icon name="log-in" class="h-4 w-4" />
            Log in
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
        Don't have an account?
        <a class="inline-flex items-center gap-1.5 font-semibold text-indigo-600 transition hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300" href="{{ route('register') }}">Create an account<x-icon name="arrow-right" class="h-4 w-4" /></a>
    </p>
</x-layouts.auth>
