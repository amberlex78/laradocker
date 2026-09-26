<x-layouts.admin :title="'Edit user'">
    <x-admin.page-header
        eyebrow="Business workspace"
        title="Edit user"
        description="Update account details without changing protected access boundaries."
    />

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
                <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10">Delete user</button>
            </form>
        </x-admin.panel>
    @endif
</x-layouts.admin>
