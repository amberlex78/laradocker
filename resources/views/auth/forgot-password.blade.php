<x-layouts.auth :title="'Forgot password'">
    <div class="flex flex-col gap-2">
        <h1 class="text-2xl font-semibold tracking-tight">Forgot your password?</h1>
        <p class="text-sm text-slate-500">Enter your email and we will send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="mt-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="mt-6 rounded-lg bg-red-50 p-4 text-sm text-red-700" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="mt-8 flex flex-col gap-5" method="POST" action="{{ route('password.email') }}">
        @csrf
        <label class="flex flex-col gap-2 text-sm font-medium" for="email">
            Email
            <input class="rounded-lg border-slate-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
        </label>
        <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" type="submit">Send reset link</button>
    </form>

    <p class="mt-6 text-center text-sm"><a class="font-medium text-indigo-600 hover:text-indigo-500" href="{{ route('login') }}">Back to login</a></p>
</x-layouts.auth>
