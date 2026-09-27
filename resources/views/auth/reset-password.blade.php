<x-layouts.auth title="Reset password">
    <x-auth.panel title="Reset your password" description="Choose a new password for your account.">
        @if ($errors->any())
            <x-ui.alert variant="danger" title="We could not reset your password" class="mb-6">
                <ul class="flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input name="token" type="hidden" value="{{ $request->route('token') }}">

            <div class="space-y-5">
                <x-ui.input
                    id="reset-email"
                    name="email"
                    type="email"
                    label="Your email"
                    :value="old('email', $request->email)"
                    placeholder="name@company.com"
                    autocomplete="email"
                    required
                />

                <x-ui.input
                    id="reset-password"
                    name="password"
                    type="password"
                    label="New password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />

                <x-ui.input
                    id="reset-password-confirmation"
                    name="password_confirmation"
                    type="password"
                    label="Confirm new password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />
            </div>

            <x-ui.button type="submit" class="w-full">Reset password</x-ui.button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Back to sign in</a>
            </p>
        </form>
    </x-auth.panel>
</x-layouts.auth>
