<x-layouts.developer :title="'Users'">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <x-admin.page-header
            title="Users"
            description="Manage all application accounts and role assignments."
        />

        <a href="{{ route('developer.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            Add user
        </a>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <x-admin.panel title="All application users" description="Every account and role is visible in this technical workspace.">
        <div class="-mx-5 overflow-x-auto sm:-mx-6">
            <table class="min-w-full text-left text-sm">
                <thead class="border-y border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 font-semibold sm:px-6">User</th>
                        <th class="px-5 py-3 font-semibold sm:px-6">Role</th>
                        <th class="px-5 py-3 font-semibold sm:px-6">Verification</th>
                        <th class="px-5 py-3 text-right font-semibold sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($users as $user)
                        <tr class="align-middle transition hover:bg-slate-50/70 dark:hover:bg-slate-950/60">
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                                    <div class="min-w-0">
                                        <a href="{{ route('developer.users.show', $user) }}" class="block truncate font-semibold text-slate-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-300">{{ $user->name }}</a>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6"><x-admin.user-role-badge :role="$user->role" /></td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                @if ($user->email_verified_at)
                                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Verified</span>
                                @else
                                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Pending</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-right sm:px-6">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('developer.users.edit', $user) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50 dark:text-indigo-300 dark:hover:bg-indigo-500/10">Edit</a>
                                    @if ($user->is(auth()->user()))
                                        <span class="px-2.5 py-1.5 text-xs text-slate-400 dark:text-slate-500">Protected</span>
                                    @else
                                        <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:text-rose-300 dark:hover:bg-rose-500/10">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8"><x-admin.empty-state title="No users yet" description="Create the first application user to get started." /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-6">{{ $users->links() }}</div>
        @endif
    </x-admin.panel>
</x-layouts.developer>
