<x-layouts.public :title="'Welcome'">
    <section class="mx-auto flex min-h-[calc(100vh-81px)] max-w-6xl items-center px-6 py-20">
        <div class="flex max-w-2xl flex-col gap-6">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-300">{{ config('app.name', 'Laravel') }}</p>
            <h1 class="text-5xl font-semibold tracking-tight text-white sm:text-6xl">Welcome</h1>
            <p class="max-w-xl text-lg leading-8 text-slate-300">A clean starting point for the public site, account area, administration, and developer workspace.</p>
            <div class="flex flex-wrap gap-3">
                @guest
                    <a class="rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400" href="{{ route('register') }}">Create an account</a>
                    <a class="rounded-lg border border-white/20 px-5 py-3 text-sm font-semibold text-white hover:bg-white/10" href="{{ route('login') }}">Log in</a>
                @else
                    <a class="rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400" href="{{ route('account') }}">Open account</a>
                @endguest
            </div>
        </div>
    </section>
</x-layouts.public>
