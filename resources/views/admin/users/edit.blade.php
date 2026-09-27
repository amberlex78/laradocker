<x-layouts.admin :title="'Edit user'">
    <x-admin.page-header
        title="Edit user"
        icon="file-pen"
        description="Update account details without changing protected access boundaries."
    />

    <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
        <x-ui.card title="User details" description="Update the account profile and role where permitted.">
            <x-admin.user-form
                :user="$user"
                :action="route('admin.users.update', $user)"
                method="PUT"
                :available-roles="$availableRoles"
                :role-editable="! ($user->role === \App\Enums\UserRole::Admin)"
                :cancel-url="route('admin.users.index')"
            />
        </x-ui.card>

        @if ($user->role !== \App\Enums\UserRole::Admin)
            <x-ui.card title="Danger zone" description="Deleting an account is permanent.">
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger"><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
                </form>
            </x-ui.card>
        @else
            <x-ui.card title="Account protection" description="You cannot delete your own account.">
                <x-ui.badge variant="gray"><x-icon name="shield-check" class="me-1 inline h-3.5 w-3.5" />Protected</x-ui.badge>
            </x-ui.card>
        @endif
    </div>
</x-layouts.admin>
