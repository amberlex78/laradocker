@props([
    'area' => 'admin',
    'navigation' => [],
])

<aside id="{{ $area }}-sidebar" class="fixed start-0 top-0 z-40 h-screen w-64 -translate-x-full border-e border-gray-700 bg-gray-800 transition-transform md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen }" aria-label="{{ $workspace['name'] }} sidebar">
    <div class="flex h-full flex-col overflow-y-auto px-3 py-4">
        <div class="mb-6 flex items-center justify-between px-2">
            <a href="{{ route($workspace['route']) }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-base bg-blue-600 text-lg font-bold text-white">{{ $workspace['initial'] }}</span>
                <span class="self-center whitespace-nowrap text-xl font-semibold text-white">{{ $workspace['name'] }}</span>
            </a>

            <button type="button" @click="sidebarOpen = false" aria-controls="{{ $area }}-sidebar" class="rounded-base p-2 text-gray-400 hover:bg-gray-700 hover:text-white md:hidden" aria-label="Close sidebar">
                <x-icon name="x" class="h-5 w-5" />
            </button>
        </div>

        <nav class="flex-1" aria-label="{{ $workspace['name'] }} navigation">
            <ul class="space-y-2 font-medium">
                @foreach ($navigation as $item)
                    @php
                        $isActive = request()->routeIs($item['route']);
                    @endphp

                    <li>
                        <x-admin.nav-item :label="$item['label']" :href="route($item['route'])" :active="$isActive">
                            <x-icon :name="$item['icon']" class="h-5 w-5" />
                        </x-admin.nav-item>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="mt-auto border-t border-gray-700 pt-4">
            <a href="{{ route('home') }}" class="group flex items-center rounded-base p-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                <x-icon name="arrow-right" class="h-5 w-5 shrink-0 text-gray-400 transition duration-75 group-hover:text-white" />
                <span class="ms-3">View public site</span>
            </a>
        </div>
    </div>
</aside>
