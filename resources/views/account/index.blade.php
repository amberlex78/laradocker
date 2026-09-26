<x-layouts.account :title="'Account'">
    <section class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400">Account</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Your account</h1>
        <p class="text-slate-600 dark:text-slate-400">Welcome back, {{ auth()->user()->name }}.</p>
    </section>
</x-layouts.account>
