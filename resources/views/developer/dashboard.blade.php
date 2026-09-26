<x-layouts.developer :title="'Developer dashboard'">
    <x-admin.page-header
        title="Developer dashboard"
        icon="layout-dashboard"
        description="Developer tools will appear here as they become available."
    />

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Application summary">
        <x-admin.stat-card label="Application status" value="Ready" description="The application shell is responding normally." tone="emerald" />
        <x-admin.stat-card label="Environment" value="{{ ucfirst(app()->environment()) }}" description="The current Laravel environment." tone="slate" />
        <x-admin.stat-card label="Technical tools" value="0" description="No developer tools have been connected yet." />
        <x-admin.stat-card label="Open incidents" value="0" description="Incident tracking will appear when monitoring is added." tone="amber" />
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <x-admin.panel title="Technical workspace" description="A place for diagnostics and engineering context.">
            <x-admin.empty-state title="No technical events yet" description="Logs, health checks and system events will appear here in a future module." />
        </x-admin.panel>

        <x-admin.panel title="Next modules" description="Potential areas for the technical workspace.">
            <ul class="grid gap-3 text-sm text-slate-600 sm:grid-cols-2">
                <li class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">Application health</li>
                <li class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">Logs and events</li>
                <li class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">Background jobs</li>
                <li class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">System configuration</li>
            </ul>
        </x-admin.panel>
    </section>
</x-layouts.developer>
