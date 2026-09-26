<x-layouts.public :title="'Welcome'">
    <section class="mx-auto flex min-h-[calc(100vh-81px)] max-w-6xl items-center px-6 py-20">
        <div class="flex max-w-2xl flex-col gap-6">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600 dark:text-indigo-300">{{ config('app.name', 'Laravel') }}</p>
            <h1 class="text-5xl font-semibold tracking-tight text-slate-950 dark:text-white sm:text-6xl">Welcome</h1>
            <p class="max-w-xl text-lg leading-8 text-slate-600 dark:text-slate-300">A clean starting point for the public site, account area, administration, and developer workspace.</p>
            <div class="flex flex-wrap gap-3">
                @guest
                    <a class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400" href="{{ route('register') }}"><x-icon name="user-plus" class="h-4 w-4" />Create an account</a>
                    <a class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 dark:border-white/20 dark:text-white dark:hover:bg-white/10" href="{{ route('login') }}"><x-icon name="log-in" class="h-4 w-4" />Log in</a>
                @else
                    <a class="inline-flex items-center gap-2 rounded-lg bg-indigo-500 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-400" href="{{ route('account') }}"><x-icon name="arrow-right" class="h-4 w-4" />Open account</a>
                @endguest
            </div>
        </div>
    </section>
</x-layouts.public>
