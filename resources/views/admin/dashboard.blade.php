<x-layouts.admin :title="'Admin dashboard'">
    <x-admin.page-header
        title="Admin dashboard"
        icon="layout-dashboard"
        description="Admin tools will appear here as they become available."
    />

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Workspace summary">
        <x-admin.stat-card label="Workspace status" value="Ready" description="The administration shell is ready for its first module." tone="emerald" />
        <x-admin.stat-card label="Configured modules" value="0" description="No business modules have been connected yet." />
        <x-admin.stat-card label="Open actions" value="0" description="There are no actions waiting for attention." tone="amber" />
        <x-admin.stat-card label="Activity events" value="0" description="Activity history will appear when modules are introduced." tone="slate" />
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <x-admin.panel title="Quick actions" description="Common actions will appear here as workflows are added.">
            <x-admin.empty-state title="No actions available" description="There are no business workflows configured yet." />
        </x-admin.panel>

        <x-admin.panel title="Recent activity" description="A concise history of changes and events across the workspace.">
            <x-admin.empty-state title="No recent activity yet" description="Activity will be collected once the first application module is connected." />
        </x-admin.panel>
    </section>
</x-layouts.admin>
