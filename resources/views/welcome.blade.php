<x-layouts.public :title="config('app.name', 'Laravel').' — Docker-ready Laravel starter'">
    <section class="relative isolate overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8 lg:py-28">
            <div>
                <x-ui.badge variant="blue">Laravel starter kit</x-ui.badge>

                <h1 class="mt-6 max-w-3xl text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl dark:text-white">
                    A Docker-ready Laravel foundation for whatever you build.
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Clone a clean Laravel base, develop in isolated containers, deploy the same foundation to production, and shape it into a blog, store, portal, or anything else you need.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @guest
                        <x-ui.link-button href="{{ route('register') }}">
                            <x-icon name="user-plus" class="h-4 w-4" />
                            Create an account
                        </x-ui.link-button>
                        <x-ui.link-button href="{{ route('login') }}" variant="secondary">
                            <x-icon name="log-in" class="h-4 w-4" />
                            Log in
                        </x-ui.link-button>
                    @else
                        <x-ui.link-button href="{{ route('account') }}">
                            <x-icon name="arrow-right" class="h-4 w-4" />
                            My profile
                        </x-ui.link-button>
                        @if ($workspace)
                            <x-ui.link-button href="{{ route($workspace['route']) }}" variant="secondary">
                                <x-icon name="layout-dashboard" class="h-4 w-4" />
                                {{ $workspace['label'] }}
                            </x-ui.link-button>
                        @endif
                    @endguest
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Development and production ready
                    </span>
                    <span>No host PHP, Node, or MariaDB required</span>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-3xl bg-blue-100/70 blur-2xl dark:bg-blue-900/20"></div>
                <x-ui.card title="Start in Docker" description="The host only needs Docker, Compose, and Make." class="relative">
                    <div class="overflow-hidden rounded-lg bg-gray-100 shadow-inner dark:bg-gray-950">
                        <div class="flex items-center gap-2 border-b border-gray-200 px-4 py-3 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            <span class="h-2.5 w-2.5 rounded-full bg-red-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-yellow-400"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>
                            <span class="ml-2">terminal</span>
                        </div>
                        <pre class="overflow-x-auto p-5 text-sm leading-7 text-gray-800 dark:text-gray-200"><code><span class="text-blue-600 dark:text-blue-400">$</span> git clone your-project
<span class="text-blue-600 dark:text-blue-400">$</span> make install
<span class="text-blue-600 dark:text-blue-400">$</span> make up
<span class="text-blue-600 dark:text-blue-400">$</span> make migrate</code></pre>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <div class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                                <x-icon name="code-2" class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                                Development
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Live source mounts and Vite HMR.</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <div class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                                <x-icon name="shield-check" class="h-4 w-4 text-green-600 dark:text-green-400" />
                                Production
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Self-contained images and services.</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </section>

    <section class="border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800/50">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-8 lg:py-20">
            <div class="max-w-2xl">
                <x-ui.badge variant="blue">UI foundation</x-ui.badge>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Tailwind CSS + Flowbite for Laravel</h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Build from a consistent UI foundation instead of starting every component from scratch. The project includes reusable Blade components that you can extend or replace as your design takes shape.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900">
                    <x-icon name="code-2" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">Tailwind CSS 4</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">Utility-first styling with a CSS-first setup.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900">
                    <x-icon name="layout-dashboard" class="h-6 w-6 text-green-600 dark:text-green-400" />
                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">Flowbite 4</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">Practical patterns for application interfaces.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900">
                    <x-icon name="file-pen" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
                    <h3 class="mt-4 font-semibold text-gray-900 dark:text-white">Blade components</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">Buttons, cards, forms, tables, modals, and more.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800/50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="max-w-3xl">
                <x-ui.badge variant="green">Ready to extend</x-ui.badge>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">A small foundation, with the important pieces already in place.</h2>
                <p class="mt-4 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Keep the infrastructure and application basics, then spend your time building the project that belongs on top of them.
                </p>
            </div>

            <div class="mt-10 grid gap-8 md:grid-cols-3">
                <x-ui.card title="Docker-first development" description="Run PHP, Composer, Node, Vite, and MariaDB in containers so the host machine stays clean.">
                    <x-ui.badge variant="blue">No local runtime required</x-ui.badge>
                </x-ui.card>

                <x-ui.card title="Development and production" description="Use separate Compose configurations for local work and self-contained production images.">
                    <x-ui.badge variant="green">Built to deploy</x-ui.badge>
                </x-ui.card>

                <x-ui.card title="Authentication and roles" description="Start with users, operators, admins, and a developer area for technical tools that ordinary admins should not touch.">
                    <x-ui.badge variant="yellow">Ready for your rules</x-ui.badge>
                </x-ui.card>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="rounded-2xl bg-blue-700 px-6 py-12 text-center shadow-lg sm:px-12 dark:bg-blue-600">
                <h2 class="text-3xl font-bold tracking-tight text-white">Clone the foundation. Build your own project.</h2>
                <p class="mx-auto mt-4 max-w-2xl text-blue-100">
                    The infrastructure is ready. The application domain is yours to define, whether you are building a blog, a store, or something entirely different.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <x-ui.link-button href="https://github.com/amberlex78/laradocker" variant="light" target="_blank" rel="noopener noreferrer">
                        <x-icon name="github" class="h-4 w-4" />
                        View repository
                    </x-ui.link-button>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-base border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:ring-4 focus:ring-white/30 focus:outline-none">
                            <x-icon name="user-plus" class="h-4 w-4" />
                            Create an account
                        </a>
                    @else
                        <a href="{{ route('account') }}" class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-base border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:ring-4 focus:ring-white/30 focus:outline-none">
                            <x-icon name="arrow-right" class="h-4 w-4" />
                            My profile
                        </a>
                        @if ($workspace)
                            <a href="{{ route($workspace['route']) }}" class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-base border border-white/60 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-white/10 focus:ring-4 focus:ring-white/30 focus:outline-none">
                                <x-icon name="layout-dashboard" class="h-4 w-4" />
                                {{ $workspace['label'] }}
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
