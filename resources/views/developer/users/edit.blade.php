<x-layouts.developer :title="'Edit user'">
    <x-admin.page-header
        title="Edit user"
        icon="file-pen"
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
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 dark:text-slate-400"><x-icon name="shield-check" class="h-4 w-4" />Protected</span>
            </x-admin.panel>
        @else
            <x-admin.panel title="Danger zone" description="Deleting an account is permanent.">
                <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger"><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
                </form>
            </x-admin.panel>
        @endif
    </div>
</x-layouts.developer>
