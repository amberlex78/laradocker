<x-layouts.admin :title="'Create user'">
    <x-admin.page-header
        title="Create user"
        icon="user-plus"
        description="Add a person to the business workspace."
    />

    <div class="w-full lg:w-1/2">
        <x-ui.card title="User details" description="Set the account details and business role.">
            <x-admin.user-form
                :action="route('admin.users.store')"
                :available-roles="$availableRoles"
                :cancel-url="route('admin.users.index')"
                submit-label="Create user"
            />
        </x-ui.card>
    </div>
</x-layouts.admin>
