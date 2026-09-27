<x-layouts.developer :title="'Create user'">
    <x-admin.page-header
        title="Create user"
        icon="user-plus"
        description="Add an application account with any available role."
    />

    <div class="w-full lg:w-1/2">
        <x-ui.card title="User details" description="Set the account details and role.">
            <x-admin.user-form
                :action="route('developer.users.store')"
                :available-roles="$availableRoles"
                :cancel-url="route('developer.users.index')"
                submit-label="Create user"
            />
        </x-ui.card>
    </div>
</x-layouts.developer>
