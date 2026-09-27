<x-layouts.developer :title="'Users'">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <x-admin.page-header
            title="Users"
            icon="users"
            description="Manage all application accounts and role assignments."
        />

        <x-ui.link-button href="{{ route('developer.users.create') }}">
            <x-icon name="user-plus" class="h-4 w-4" />
            Add user
        </x-ui.link-button>
    </div>

    @if (session('status'))
        <x-ui.alert variant="success">{{ session('status') }}</x-ui.alert>
    @endif

    <x-ui.card title="All application users" description="Every account and role is visible in this technical workspace.">
        <x-ui.table>
            <x-ui.table.head>
                <x-ui.table.cell as="th" variant="header" scope="col">User</x-ui.table.cell>
                <x-ui.table.cell as="th" variant="header" scope="col">Role</x-ui.table.cell>
                <x-ui.table.cell as="th" variant="header" scope="col">Verification</x-ui.table.cell>
                <x-ui.table.cell as="th" variant="header" scope="col" class="text-right">Actions</x-ui.table.cell>
            </x-ui.table.head>
            <tbody>
                @forelse ($users as $user)
                    <x-ui.table.row>
                        <x-ui.table.cell as="th" scope="row">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                                <div class="min-w-0">
                                    <a href="{{ route('developer.users.show', $user) }}" class="block truncate font-semibold text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-blue-400">{{ $user->name }}</a>
                                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </x-ui.table.cell>
                        <x-ui.table.cell><x-admin.user-role-badge :role="$user->role" /></x-ui.table.cell>
                        <x-ui.table.cell>
                            @if ($user->email_verified_at)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600 dark:text-green-400"><x-icon name="circle-check" class="h-3.5 w-3.5" />Verified</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 dark:text-gray-400"><x-icon name="clock" class="h-3.5 w-3.5" />Pending</span>
                            @endif
                        </x-ui.table.cell>
                        <x-ui.table.cell class="text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('developer.users.edit', $user) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-base text-blue-600 hover:bg-blue-50 focus:ring-4 focus:ring-blue-300 dark:text-blue-400 dark:hover:bg-gray-700 dark:focus:ring-blue-800" aria-label="Edit user" title="Edit user"><x-icon name="file-pen" class="h-5 w-5" /></a>
                                @if ($user->is(auth()->user()))
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-base text-gray-400 dark:text-gray-500" role="img" aria-label="Protected" title="Protected"><x-icon name="shield-check" class="h-5 w-5" /></span>
                                @else
                                    <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-base text-red-600 hover:bg-red-50 focus:ring-4 focus:ring-red-300 dark:text-red-400 dark:hover:bg-gray-700 dark:focus:ring-red-900" aria-label="Delete user" title="Delete user"><x-icon name="trash" class="h-5 w-5" /></button>
                                    </form>
                                @endif
                            </div>
                        </x-ui.table.cell>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.cell colspan="4" class="py-8"><x-admin.empty-state title="No users yet" description="Create the first application user to get started." /></x-ui.table.cell>
                    </x-ui.table.row>
                @endforelse
            </tbody>

            @if ($users->hasPages())
                <x-slot:footer>
                    <x-ui.table.pagination :paginator="$users" label="users" />
                </x-slot:footer>
            @endif
        </x-ui.table>
    </x-ui.card>
</x-layouts.developer>
