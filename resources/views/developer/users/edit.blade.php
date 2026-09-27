<x-layouts.developer :title="'Edit user'">
    <x-admin.page-header
        title="Edit user"
        icon="file-pen"
        description="Update application details and role assignments."
    />

    <div class="grid gap-6 lg:grid-cols-2 lg:items-stretch">
        <x-ui.card title="User details" description="Update the account profile, password, and role.">
            <x-admin.user-form
                :user="$user"
                :action="route('developer.users.update', $user)"
                method="PUT"
                :available-roles="$availableRoles"
                :cancel-url="route('developer.users.index')"
            />
        </x-ui.card>

        @if ($user->is(auth()->user()))
            <x-ui.card title="Account protection" description="You cannot delete your own account.">
                <x-ui.badge variant="gray"><x-icon name="shield-check" class="me-1 inline h-3.5 w-3.5" />Protected</x-ui.badge>
            </x-ui.card>
        @else
            <x-ui.card title="Danger zone" description="Deleting an account is permanent.">
                <x-ui.button variant="danger" @click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
            </x-ui.card>

            <x-ui.confirmation-modal
                id="delete-user-{{ $user->id }}"
                :title="'Are you sure you want to delete ' . $user->name . '?'"
                :action="route('developer.users.destroy', $user)"
            >
                This action cannot be undone.
            </x-ui.confirmation-modal>
        @endif
    </div>
</x-layouts.developer>
