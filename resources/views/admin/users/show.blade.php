<x-layouts.admin :title="'User details'">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <x-admin.page-header title="User details" icon="user-round" description="Review this account's business access." />
        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white transition hover:bg-indigo-700" aria-label="Edit user" title="Edit user"><x-icon name="file-pen" class="h-5 w-5" /></a>
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
</x-layouts.admin>
