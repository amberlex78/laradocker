<x-layouts.admin :title="'Create user'">
    <x-admin.page-header
        eyebrow="Business workspace"
        title="Create user"
        description="Add a person to the business workspace."
    />

    <x-admin.panel title="User details" description="Set the account details and business role.">
        <x-admin.user-form
            :action="route('admin.users.store')"
            :available-roles="$availableRoles"
            :cancel-url="route('admin.users.index')"
            submit-label="Create user"
        />
    </x-admin.panel>
</x-layouts.admin>
