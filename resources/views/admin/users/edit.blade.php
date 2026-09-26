<x-layouts.admin :title="'Edit user'">
    <x-admin.page-header
        title="Edit user"
        icon="file-pen"
        description="Update account details without changing protected access boundaries."
    />

    <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
        <x-admin.panel title="User details" description="Update the account profile and role where permitted.">
            <x-admin.user-form
                :user="$user"
                :action="route('admin.users.update', $user)"
                method="PUT"
                :available-roles="$availableRoles"
                :role-editable="! ($user->role === \App\Enums\UserRole::Admin)"
                :cancel-url="route('admin.users.index')"
            />
        </x-admin.panel>

        @if ($user->role !== \App\Enums\UserRole::Admin)
            <x-admin.panel title="Danger zone" description="Deleting an account is permanent.">
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10"><x-icon name="trash" class="h-4 w-4" />Delete user</button>
                </form>
            </x-admin.panel>
        @else
            <x-admin.panel title="Account protection" description="You cannot delete your own account.">
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 dark:text-slate-400"><x-icon name="shield-check" class="h-4 w-4" />Protected</span>
            </x-admin.panel>
        @endif
    </div>
</x-layouts.admin>
