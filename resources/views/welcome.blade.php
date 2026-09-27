<x-layouts.public :title="'Welcome'">
    <section class="relative isolate overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8 lg:py-28">
            <div>
                <x-ui.badge variant="blue">A simpler way to work</x-ui.badge>

                <h1 class="mt-6 max-w-3xl text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl dark:text-white">
                    Everything your team needs to move forward.
                </h1>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Bring projects, people, and priorities together in one calm workspace built for focused teams.
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
                            Open account
                        </x-ui.link-button>
                    @endguest
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Ready for your next project
                    </span>
                    <span>No complicated setup</span>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-3xl bg-blue-100/70 blur-2xl dark:bg-blue-900/20"></div>
                <x-ui.card title="Workspace overview" description="A clear view of what matters today." class="relative">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700/50">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Weekly progress</p>
                                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">84%</p>
                            </div>
                            <x-ui.badge variant="green">On track</x-ui.badge>
                        </div>

                        <div class="mt-5 h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-600">
                            <div class="h-2.5 w-[84%] rounded-full bg-blue-600"></div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Active projects</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">24</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Team members</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">12</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </section>

    <section class="border-y border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800/50">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 md:grid-cols-3 lg:px-8">
            <x-ui.card title="Stay aligned" description="Keep goals, tasks, and conversations connected so everyone knows what comes next.">
                <x-ui.badge variant="blue">Shared context</x-ui.badge>
            </x-ui.card>

            <x-ui.card title="Move with focus" description="See the important work at a glance and spend less time searching for updates.">
                <x-ui.badge variant="green">Clear priorities</x-ui.badge>
            </x-ui.card>

            <x-ui.card title="Grow together" description="Give your team a simple place to review progress, celebrate wins, and improve.">
                <x-ui.badge variant="yellow">Built for focused teams</x-ui.badge>
            </x-ui.card>
        </div>
    </section>

    <section class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="rounded-2xl bg-blue-700 px-6 py-12 text-center shadow-lg sm:px-12 dark:bg-blue-600">
                <h2 class="text-3xl font-bold tracking-tight text-white">Ready to make your next step simpler?</h2>
                <p class="mx-auto mt-4 max-w-2xl text-blue-100">
                    Start with a focused workspace and build a rhythm your team can rely on.
                </p>
                @guest
                    <x-ui.link-button href="{{ route('register') }}" variant="light" class="mt-8">
                        Create your workspace
                    </x-ui.link-button>
                @else
                    <x-ui.link-button href="{{ route('account') }}" variant="light" class="mt-8">
                        Open your account
                    </x-ui.link-button>
                @endguest
            </div>
        </div>
    </section>
</x-layouts.public>
