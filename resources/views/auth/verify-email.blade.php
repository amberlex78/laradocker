<x-layouts.auth :title="'Verify email'">
    <div class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600 dark:text-indigo-400">One more step</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Verify your email</h1>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">We sent a verification link to <span class="font-medium text-slate-700 dark:text-slate-300">{{ auth()->user()->email }}</span>. Confirm your address to keep your account secure.</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <x-auth.alert class="mt-6" title="Verification link sent" type="success">A new verification link has been sent to your email address.</x-auth.alert>
    @endif

    @if ($errors->any())
        <x-auth.alert class="mt-6" title="We could not send the verification link">
            {{ $errors->first() }}
        </x-auth.alert>
    @endif

    <div class="mt-8 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-indigo-600 px-4 text-sm font-semibold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/20" type="submit">
                Resend verification email
            </button>
        </form>

        <form class="text-center" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm font-medium text-slate-500 transition hover:text-slate-950 dark:text-slate-400 dark:hover:text-white" type="submit">Log out</button>
        </form>
    </div>
</x-layouts.auth>
