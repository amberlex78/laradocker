<x-layouts.auth title="Create account">
    <x-auth.panel title="Create your account" description="Start building with the application workspace.">
        @if ($errors->any())
            <x-ui.alert variant="danger" title="Please check the form" class="mb-6">
                <ul class="flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form class="space-y-6" action="{{ route('register') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <x-ui.input
                    id="register-name"
                    name="name"
                    label="Full name"
                    :value="old('name')"
                    placeholder="Bonnie Green"
                    autocomplete="name"
                    required
                />

                <x-ui.input
                    id="register-email"
                    name="email"
                    type="email"
                    label="Email"
                    :value="old('email')"
                    placeholder="name@company.com"
                    autocomplete="email"
                    required
                />

                <x-ui.password-input
                    id="register-password"
                    name="password"
                    label="Password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />

                <x-ui.password-input
                    id="register-password-confirmation"
                    name="password_confirmation"
                    label="Confirm password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />
            </div>

            <x-ui.button type="submit" class="w-full">Create account</x-ui.button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign in</a>
            </p>
        </form>
    </x-auth.panel>
</x-layouts.auth>
