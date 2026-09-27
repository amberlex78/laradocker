<x-layouts.admin :title="'Admin dashboard'">
    <x-admin.page-header
        title="Admin dashboard"
        description="A focused overview of the business administration area."
    >
        @if (auth()->user()->role === \App\Enums\UserRole::Admin)
            <x-ui.link-button href="{{ route('admin.users.index') }}" size="sm" aria-label="Manage users">
                Manage users
            </x-ui.link-button>
        @endif
    </x-admin.page-header>

    <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4" aria-label="Workspace summary">
        <x-ui.card title="Workspace status" description="The administration shell is ready for its first module.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">Ready</p>
        </x-ui.card>
        <x-ui.card title="Configured modules" description="No business modules have been connected yet.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
        </x-ui.card>
        <x-ui.card title="Open actions" description="There are no actions waiting for attention.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
        </x-ui.card>
        <x-ui.card title="Activity events" description="Activity history will appear when modules are introduced.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
        </x-ui.card>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-3" aria-label="Workspace activity">
        <x-ui.card title="Quick actions" description="Common actions will appear here as workflows are added.">
            <x-admin.empty-state title="No actions available" description="There are no business workflows configured yet." />
        </x-ui.card>

        <x-ui.card title="Recent activity" description="A concise history of changes and events across the workspace." class="xl:col-span-2">
            <x-admin.empty-state title="No recent activity yet" description="Activity will be collected once the first application module is connected." />
        </x-ui.card>
    </section>
</x-layouts.admin>
