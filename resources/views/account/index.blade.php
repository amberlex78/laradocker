<x-layouts.account :title="'Account'">
    <section class="flex flex-col gap-3">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">Account</p>
        <h1 class="text-3xl font-semibold tracking-tight">Your account</h1>
        <p class="text-slate-600">Welcome back, {{ auth()->user()->name }}.</p>
    </section>
</x-layouts.account>
