<x-layouts.account :title="'Account'">
    <div class="flex flex-col gap-6">
        @if (session('status') === \Laravel\Fortify\Fortify::PROFILE_INFORMATION_UPDATED)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                Your profile information has been updated.
            </div>
        @elseif (session('status') === \Laravel\Fortify\Fortify::PASSWORD_UPDATED)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                Your password has been updated.
            </div>
        @endif

        <div class="grid items-start gap-6 lg:grid-cols-2">
            <x-admin.panel title="Profile information" description="Update your profile information and email address.">
                <form method="POST" action="{{ route('user-profile-information.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-auth.field
                            name="name"
                            label="Name"
                            :value="auth()->user()->name"
                            autocomplete="name"
                            placeholder="Your name"
                            error-bag="updateProfileInformation"
                            required
                            autofocus
                        />

                        <x-auth.field
                            name="email"
                            label="Email"
                            type="email"
                            :value="auth()->user()->email"
                            autocomplete="email"
                            placeholder="you@example.com"
                            error-bag="updateProfileInformation"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-end border-t border-slate-100 pt-5 dark:border-slate-800">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                            <x-icon name="save" class="h-4 w-4" />
                            Save changes
                        </button>
                    </div>
                </form>
            </x-admin.panel>

            <x-admin.panel title="Update password" description="Use a strong, unique password to keep your account secure.">
                <form method="POST" action="{{ route('user-password.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-auth.password-field
                                name="current_password"
                                label="Current password"
                                autocomplete="current-password"
                                placeholder="Enter your current password"
                                error-bag="updatePassword"
                                required
                            />
                        </div>

                        <x-auth.password-field
                            name="password"
                            label="New password"
                            autocomplete="new-password"
                            placeholder="Create a new password"
                            error-bag="updatePassword"
                            required
                        />

                        <x-auth.password-field
                            name="password_confirmation"
                            label="Confirm password"
                            autocomplete="new-password"
                            placeholder="Repeat your new password"
                            error-bag="updatePassword"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-end border-t border-slate-100 pt-5 dark:border-slate-800">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/30">
                            <x-icon name="save" class="h-4 w-4" />
                            Update password
                        </button>
                    </div>
                </form>
            </x-admin.panel>
        </div>
    </div>
</x-layouts.account>
