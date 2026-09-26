<x-layouts.developer :title="'Edit user'">
    <x-admin.page-header
        eyebrow="Technical workspace"
        title="Edit user"
        description="Update application details and role assignments."
    />

    <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
        <x-admin.panel title="User details" description="Update the account profile, password, and role.">
            <x-admin.user-form
                :user="$user"
                :action="route('developer.users.update', $user)"
                method="PUT"
                :available-roles="$availableRoles"
                :cancel-url="route('developer.users.index')"
            />
        </x-admin.panel>

        @if ($user->is(auth()->user()))
            <x-admin.panel title="Account protection" description="You cannot delete your own account.">
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Protected</span>
            </x-admin.panel>
        @else
            <x-admin.panel title="Danger zone" description="Deleting an account is permanent.">
                <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10">Delete user</button>
                </form>
            </x-admin.panel>
        @endif
    </div>
</x-layouts.developer>
