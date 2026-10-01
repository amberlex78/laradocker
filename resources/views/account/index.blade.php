@php
    $profileErrors = $errors->getBag('updateProfileInformation');
    $passwordErrors = $errors->getBag('updatePassword');
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
        @elseif (session('status') === 'active-sessions-terminated')
            <x-ui.alert variant="success">
                All other active sessions have been terminated.
            </x-ui.alert>
        @endif

        <header>
            <p class="text-sm font-medium uppercase tracking-wide text-blue-700 dark:text-blue-400">Account settings</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Manage your account</h1>
            <p class="mt-3 max-w-2xl text-gray-600 dark:text-gray-400">Update your profile details and keep your password secure.</p>
        </header>

        <div class="grid gap-6 lg:grid-cols-2 lg:items-start">
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
                            value="{{ old('name', $user->name) }}"
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
                            value="{{ old('email', $user->email) }}"
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

            <div class="flex flex-col gap-6">
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
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $user->last_login_browser ?: 'Unknown' }}
                                    @if ($user->last_login_browser_version)
                                        ({{ $user->last_login_browser_version }})
                                    @endif
                                </dd>
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
            </div>
        </div>

        <x-ui.card title="Active sessions" description="Devices currently signed in to your account." id="active-sessions">
            <x-slot:actions>
                <form method="POST" action="{{ route('account.sessions.destroy-other') }}">
                    @csrf

                    <x-ui.button type="submit" variant="danger">
                        <x-icon name="log-out" class="h-4 w-4" />
                        Terminate other sessions
                    </x-ui.button>
                </form>
            </x-slot:actions>

            <x-ui.table striped hoverable>
                <x-ui.table.head>
                    <x-ui.table.cell as="th" variant="header" scope="col">Device</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">IP address</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Last activity</x-ui.table.cell>
                </x-ui.table.head>
                <tbody>
                    @forelse ($activeSessions as $activeSession)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $activeSession['browser'] ?: 'Unknown' }}
                                        @if ($activeSession['browser_version'])
                                            {{ $activeSession['browser_version'] }}
                                        @endif
                                        @if ($activeSession['operating_system'])
                                            / {{ $activeSession['operating_system'] }}
                                        @endif
                                    </span>
                                    @if ($activeSession['is_current'])
                                        <x-ui.badge variant="green">Current</x-ui.badge>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ str($activeSession['device_type'] ?: 'unknown')->headline() }}
                                    @if ($activeSession['device_model'])
                                        ({{ $activeSession['device_model'] }})
                                    @endif
                                </p>
                            </x-ui.table.cell>
                            <x-ui.table.cell>{{ $activeSession['ip_address'] ?: 'Unknown' }}</x-ui.table.cell>
                            <x-ui.table.cell>{{ $activeSession['last_activity']?->format('F j, Y g:i A') ?: 'Unknown' }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.row>
                            <x-ui.table.cell colspan="3" class="py-8 text-center">No active sessions available.</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforelse
                </tbody>
            </x-ui.table>
        </x-ui.card>

        <x-ui.card title="Login history" description="Recent successful sign-ins from your account." id="login-history">
            <x-ui.table striped hoverable>
                <x-ui.table.head>
                    <x-ui.table.cell as="th" variant="header" scope="col">Date and time</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Device</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Operating system</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">Browser</x-ui.table.cell>
                    <x-ui.table.cell as="th" variant="header" scope="col">IP address</x-ui.table.cell>
                </x-ui.table.head>
                <tbody>
                    @forelse ($loginHistories as $loginHistory)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                {{ $loginHistory->logged_in_at?->format('F j, Y g:i A') ?: 'Unknown' }}
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                {{ str($loginHistory->device_type ?: 'unknown')->headline() }}
                                @if ($loginHistory->device_model)
                                    ({{ $loginHistory->device_model }})
                                @endif
                            </x-ui.table.cell>
                            <x-ui.table.cell>{{ $loginHistory->operating_system ?: 'Unknown' }}</x-ui.table.cell>
                            <x-ui.table.cell>
                                {{ $loginHistory->browser ?: 'Unknown' }}
                                @if ($loginHistory->browser_version)
                                    ({{ $loginHistory->browser_version }})
                                @endif
                            </x-ui.table.cell>
                            <x-ui.table.cell>{{ $loginHistory->ip_address ?: 'Unknown' }}</x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.row>
                            <x-ui.table.cell colspan="5" class="py-8 text-center">No login history available.</x-ui.table.cell>
                        </x-ui.table.row>
                    @endforelse
                </tbody>

                @if ($loginHistories->hasPages())
                    <x-slot:footer>
                        <x-ui.table.pagination :paginator="$loginHistories" label="logins" />
                    </x-slot:footer>
                @endif
            </x-ui.table>
        </x-ui.card>

    </div>
</x-layouts.account>
