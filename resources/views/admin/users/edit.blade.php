<x-layouts.admin :title="'Edit user'">
    <x-admin.page-header
        title="Edit user"
        icon="file-pen"
        description="{{ $canEdit ? 'Update account details without changing protected access boundaries.' : 'This administrator account is read-only.' }}"
    />

    <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
        <x-ui.card title="User details" description="{{ $canEdit ? 'Update the account profile and role where permitted.' : 'This account is protected and cannot be edited.' }}">
            <x-admin.user-form
                :user="$user"
                :action="route('admin.users.update', $user)"
                method="PUT"
                :available-roles="$availableRoles"
                :role-editable="$canEdit && $user->role !== \App\Enums\UserRole::Admin"
                :fields-editable="$canEdit"
                :cancel-url="route('admin.users.index')"
            />
        </x-ui.card>

        @if ($user->role !== \App\Enums\UserRole::Admin)
            <x-ui.card title="Danger zone" description="Deleting an account is permanent.">
                <x-ui.button variant="danger" @click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
            </x-ui.card>

            <x-ui.confirmation-modal
                id="delete-user-{{ $user->id }}"
                :title="'Are you sure you want to delete ' . $user->name . '?'"
                :action="route('admin.users.destroy', $user)"
            >
                This action cannot be undone.
            </x-ui.confirmation-modal>
        @else
            <x-ui.card title="Account protection" description="You cannot delete your own account.">
                <x-ui.button variant="danger" disabled><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
            </x-ui.card>
        @endif
    </div>
</x-layouts.admin>
