<x-layouts.auth :title="'Log in'">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">{{ config('app.name', 'Laravel') }}</p>
        <h1 class="text-2xl font-semibold tracking-tight">Log in to your account</h1>
        <p class="text-sm text-slate-500">Enter your credentials to continue.</p>
    </div>

    @if ($errors->any())
        <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">
            <ul class="flex flex-col gap-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('login') }}">
        @csrf

        <label class="flex flex-col gap-2 text-sm font-medium" for="email">
            Email
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </label>

        <label class="flex flex-col gap-2 text-sm font-medium" for="password">
            Password
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="password" name="password" type="password" required autocomplete="current-password">
        </label>

        <label class="flex items-center gap-2 text-sm text-slate-600" for="remember">
            <input class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" id="remember" name="remember" type="checkbox">
            Remember me
        </label>

        <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" type="submit">
            Log in
        </button>
    </form>

    <div class="mt-6 flex items-center justify-between text-sm">
        <a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">Forgot password?</a>
        <a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('register') }}">Create account</a>
    </div>
</x-layouts.auth>
