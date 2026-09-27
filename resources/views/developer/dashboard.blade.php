<x-layouts.developer :title="'Developer dashboard'">
    <x-admin.page-header
        title="Developer dashboard"
        description="Developer tools will appear here as they become available."
    />

    <section class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4" aria-label="Application summary">
        <x-ui.card title="Application status" description="The application shell is responding normally.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">Ready</p>
        </x-ui.card>
        <x-ui.card title="Environment" description="The current Laravel environment.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ ucfirst(app()->environment()) }}</p>
        </x-ui.card>
        <x-ui.card title="Technical tools" description="No developer tools have been connected yet.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
        </x-ui.card>
        <x-ui.card title="Open incidents" description="Incident tracking will appear when monitoring is added.">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
        </x-ui.card>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="Technical workspace" description="A place for diagnostics and engineering context.">
            <x-admin.empty-state title="No technical events yet" description="Logs, health checks and system events will appear here in a future module." />
        </x-ui.card>

        <x-ui.card title="Next modules" description="Potential areas for the technical workspace.">
            <ul class="grid gap-3 text-sm text-gray-500 dark:text-gray-400 sm:grid-cols-2">
                <li class="rounded-base border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">Application health</li>
                <li class="rounded-base border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">Logs and events</li>
                <li class="rounded-base border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">Background jobs</li>
                <li class="rounded-base border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">System configuration</li>
            </ul>
        </x-ui.card>
    </section>
</x-layouts.developer>
