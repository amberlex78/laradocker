<x-layouts.developer :title="'User details'">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <x-admin.page-header eyebrow="Technical workspace" title="User details" description="Review this account's application access." />
        <a href="{{ route('developer.users.edit', $user) }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Edit user</a>
    </div>

    <x-admin.panel title="{{ $user->name }}" description="{{ $user->email }}">
        <div class="flex items-center gap-3">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-lg font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200">{{ str($user->name)->substr(0, 1)->upper() }}</span>
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                <div class="mt-2"><x-admin.user-role-badge :role="$user->role" /></div>
            </div>
        </div>
    </x-admin.panel>

    @if ($user->is(auth()->user()))
        <x-admin.panel title="Account protection" description="You cannot delete your own account.">
            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Protected</span>
        </x-admin.panel>
    @else
        <x-admin.panel title="Danger zone" description="Deleting an account is permanent.">
            <form method="POST" action="{{ route('developer.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10">Delete user</button>
            </form>
        </x-admin.panel>
    @endif
</x-layouts.developer>
