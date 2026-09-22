<x-layouts.auth :title="'Verify email'">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold tracking-tight">Verify your email</h1>
        <p class="text-sm text-slate-500">Please confirm your email address using the link we sent you.</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mt-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700" role="status">A new verification link has been sent.</div>
    @endif

    <div class="mt-8 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" type="submit">Resend verification email</button>
        </form>

        <form class="text-center" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm font-medium text-slate-600 hover:text-slate-900" type="submit">Log out</button>
        </form>
    </div>
</x-layouts.auth>
