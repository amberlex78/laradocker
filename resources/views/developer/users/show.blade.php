<x-layouts.developer :title="'User details'">
    <x-admin.page-header title="User details" icon="user-round" description="Review this account's application access.">
        <x-ui.link-button href="{{ route('developer.users.edit', $user) }}" size="md">
            <x-icon name="file-pen" class="h-4 w-4" />
            Edit User
        </x-ui.link-button>
    </x-admin.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-ui.card title="{{ $user->name }}" description="{{ $user->email }}">
            <div class="flex items-center gap-3">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-lg font-semibold text-blue-700 dark:bg-blue-900 dark:text-blue-300">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    <div class="mt-2"><x-admin.user-role-badge :role="$user->role" /></div>
                </div>
            </div>
        </x-ui.card>

        @if ($user->is(auth()->user()))
            <x-ui.card title="Account protection" description="You cannot delete your own account.">
                <x-ui.badge variant="gray"><x-icon name="shield-check" class="me-1 inline h-3.5 w-3.5" />Protected</x-ui.badge>
            </x-ui.card>
        @else
            <x-ui.card title="Danger zone" description="Deleting an account is permanent.">
                <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger"><x-icon name="trash" class="h-4 w-4" />Delete user</x-ui.button>
                </form>
            </x-ui.card>
        @endif
    </div>
</x-layouts.developer>
