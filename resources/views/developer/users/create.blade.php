<x-layouts.developer :title="'Create user'">
    <x-admin.page-header
        eyebrow="Technical workspace"
        title="Create user"
        description="Add an application account with any available role."
    />

    <x-admin.panel title="User details" description="Set the account details and role.">
        <x-admin.user-form
            :action="route('developer.users.store')"
            :available-roles="$availableRoles"
            :cancel-url="route('developer.users.index')"
            submit-label="Create user"
        />
    </x-admin.panel>
</x-layouts.developer>
