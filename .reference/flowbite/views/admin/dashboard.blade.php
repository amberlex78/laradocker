<x-layouts.admin title="Dashboard">
    <div class="mb-8 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Overview</p>
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Admin dashboard</h2>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Full-width admin shell with an independent sidebar, navbar and content area.</p>
        </div>
        <x-ui.button>New action</x-ui.button>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.card title="Users" description="Total registered users">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">2,450</p>
            <p class="mt-2 text-sm text-green-600 dark:text-green-400">+12.5% this month</p>
        </x-ui.card>
        <x-ui.card title="Revenue" description="Current month">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">$24,780</p>
            <p class="mt-2 text-sm text-green-600 dark:text-green-400">+8.2% this month</p>
        </x-ui.card>
        <x-ui.card title="Projects" description="Active workspaces">
            <p class="text-3xl font-bold text-gray-900 dark:text-white">48</p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">6 need attention</p>
        </x-ui.card>
        <x-ui.card title="System status" description="Infrastructure">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <span class="text-xl font-semibold text-gray-900 dark:text-white">Operational</span>
            </div>
        </x-ui.card>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <x-ui.card title="Recent activity" description="A flexible content area for future dashboard widgets" class="xl:col-span-2">
            <x-ui.table>
                <x-ui.table.head>
                    <x-ui.table.cell as="th" variant="header" scope="col">Activity</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Status</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Time</x-ui.table.cell>
                </x-ui.table.head>
                <tbody>
                    <x-ui.table.row>
                        <x-ui.table.cell as="th" scope="row">New workspace created</x-ui.table.cell>
                        <x-ui.table.cell><x-ui.badge variant="green">Completed</x-ui.badge></x-ui.table.cell>
                        <x-ui.table.cell>5 min ago</x-ui.table.cell>
                    </x-ui.table.row>
                    <x-ui.table.row>
                        <x-ui.table.cell as="th" scope="row">User invited to team</x-ui.table.cell>
                        <x-ui.table.cell><x-ui.badge variant="blue">Pending</x-ui.badge></x-ui.table.cell>
                        <x-ui.table.cell>18 min ago</x-ui.table.cell>
                    </x-ui.table.row>
                </tbody>
            </x-ui.table>
        </x-ui.card>

        <x-ui.card title="Shell notes" description="Where future features will go">
            <ul class="space-y-3 text-sm text-gray-500 dark:text-gray-400">
                <li class="flex gap-2"><span class="text-blue-600">•</span> Resources stay inside the admin namespace.</li>
                <li class="flex gap-2"><span class="text-blue-600">•</span> Middleware can be added to the admin route group.</li>
                <li class="flex gap-2"><span class="text-blue-600">•</span> Individual pages control their own content width.</li>
            </ul>
        </x-ui.card>
    </div>
</x-layouts.admin>
