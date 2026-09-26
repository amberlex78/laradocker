@props([
    'area' => 'admin',
    'navigation' => [],
])

@php
    $isDeveloper = $area === 'developer';
    $brandLabel = $isDeveloper ? 'Developer workspace' : 'Admin workspace';
    $sidebarClasses = 'border-slate-200 bg-white text-slate-900 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100';
    $mutedTextClasses = 'text-slate-400 dark:text-slate-500';
    $inactiveLinkClasses = 'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white';
    $activeLinkClasses = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200';
@endphp

<aside
    id="{{ $area }}-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-72 shrink-0 -translate-x-full flex-col border-r transition-transform duration-300 lg:translate-x-0 {{ $sidebarClasses }}"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    aria-label="{{ $brandLabel }}"
>
    <div class="flex h-20 items-center justify-between border-b border-slate-200 px-6 dark:border-slate-800">
        <a class="flex min-w-0 items-center gap-3" href="{{ route($isDeveloper ? 'developer.dashboard' : 'admin.dashboard') }}">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-600 text-sm font-bold text-white">{{ $isDeveloper ? 'D' : 'A' }}</span>
            <span class="min-w-0">
                <span class="block truncate text-sm font-semibold">{{ config('app.name', 'Laravel') }}</span>
                <span class="block truncate text-xs {{ $mutedTextClasses }}">{{ $brandLabel }}</span>
            </span>
        </a>

        <button
            type="button"
            class="rounded-lg p-2 {{ $mutedTextClasses }} hover:bg-black/5 hover:text-current dark:hover:bg-white/10 lg:hidden"
            aria-label="Close navigation"
            @click="sidebarOpen = false"
        >
            <x-icon name="x" class="h-5 w-5" />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4" aria-label="{{ $brandLabel }} navigation">
        <p class="px-3 pb-3 text-[11px] font-semibold uppercase tracking-[0.18em] {{ $mutedTextClasses }}">Workspace</p>

        <div class="flex flex-col gap-1">
            @foreach ($navigation as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                @endphp

                <a
                    class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition {{ $isActive ? $activeLinkClasses : $inactiveLinkClasses }}"
                    href="{{ route($item['route']) }}"
                    @if ($isActive) aria-current="page" @endif
                >
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center">
                        <x-icon :name="$item['icon']" class="h-5 w-5" />
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="border-t border-slate-200 p-4 dark:border-slate-800">
        <a class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium {{ $inactiveLinkClasses }}" href="{{ route('account') }}">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200">
                {{ str($user = auth()->user()->name)->substr(0, 1)->upper() }}
            </span>
            <x-icon name="user-round" class="h-4 w-4 shrink-0" />
            <span class="min-w-0 truncate">Account settings</span>
        </a>
    </div>
</aside>
