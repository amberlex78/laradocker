@php
    $profileErrors = $errors->getBag('updateProfileInformation');
    $passwordErrors = $errors->getBag('updatePassword');
    $user = auth()->user();
@endphp

<x-layouts.account :title="'Account'">
    <div class="flex flex-col gap-8">
        @if (session('status') === \Laravel\Fortify\Fortify::PROFILE_INFORMATION_UPDATED)
            <x-ui.alert variant="success">
                Your profile information has been updated.
            </x-ui.alert>
        @elseif (session('status') === \Laravel\Fortify\Fortify::PASSWORD_UPDATED)
            <x-ui.alert variant="success">
                Your password has been updated.
            </x-ui.alert>
        @endif

        <header>
            <p class="text-sm font-medium uppercase tracking-wide text-blue-700 dark:text-blue-400">Account settings</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Manage your account</h1>
            <p class="mt-3 max-w-2xl text-gray-600 dark:text-gray-400">Update your profile details and keep your password secure.</p>
        </header>

        <x-ui.card title="Latest login device" description="Information detected during your most recent sign-in." id="latest-login-device">
            @if ($user->last_login_at)
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ str($user->last_login_device_type ?: 'unknown')->headline() }}
                            @if ($user->last_login_device_model)
                                ({{ $user->last_login_device_model }})
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Operating system</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->last_login_os ?: 'Unknown' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Browser</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->last_login_browser ?: 'Unknown' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last login</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->last_login_at->format('F j, Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP address</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->last_login_ip_address ?: 'Unknown' }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400">No login information available.</p>
            @endif
        </x-ui.card>

        <div class="flex flex-col gap-6">
            <x-ui.card title="Profile information" description="Update your profile information and email address." id="profile-information">
                @if ($profileErrors->any())
                    <x-ui.alert variant="danger" title="We could not update your profile information." class="mb-6">
                        <ul class="list-disc space-y-1 ps-5">
                            @foreach ($profileErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form method="POST" action="{{ route('user-profile-information.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <x-ui.input
                            id="name"
                            name="name"
                            label="Name"
                            value="{{ old('name', auth()->user()->name) }}"
                            placeholder="Your name"
                            autocomplete="name"
                            required
                            autofocus
                        />

                        <x-ui.input
                            id="email"
                            name="email"
                            type="email"
                            label="Email"
                            value="{{ old('email', auth()->user()->email) }}"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 pt-5 dark:border-gray-700">
                        <x-ui.button type="submit">
                            <x-icon name="save" class="h-4 w-4" />
                            Save changes
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            <x-ui.card title="Update password" description="Use a strong, unique password to keep your account secure." id="update-password">
                @if ($passwordErrors->any())
                    <x-ui.alert variant="danger" title="We could not update your password." class="mb-6">
                        <ul class="list-disc space-y-1 ps-5">
                            @foreach ($passwordErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form method="POST" action="{{ route('user-password.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="w-full sm:col-span-2">
                            <x-ui.password-input
                                id="current_password"
                                name="current_password"
                                label="Current password"
                                placeholder="Enter your current password"
                                autocomplete="current-password"
                                required
                            />
                        </div>

                        <x-ui.password-input
                            id="password"
                            name="password"
                            label="New password"
                            placeholder="Create a new password"
                            autocomplete="new-password"
                            required
                        />

                        <x-ui.password-input
                            id="password_confirmation"
                            name="password_confirmation"
                            label="Confirm password"
                            placeholder="Repeat your new password"
                            autocomplete="new-password"
                            required
                        />
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 pt-5 dark:border-gray-700">
                        <x-ui.button type="submit">
                            <x-icon name="save" class="h-4 w-4" />
                            Update password
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layouts.account>
