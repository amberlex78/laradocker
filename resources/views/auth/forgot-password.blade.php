<x-layouts.auth title="Forgot password">
    <x-auth.panel title="Forgot your password?" description="Enter your email and we will send you a password reset link.">
        @if (session('status'))
            <x-ui.alert variant="success" title="Check your inbox" class="mb-6">{{ session('status') }}</x-ui.alert>
        @endif

        @if ($errors->any())
            <x-ui.alert variant="danger" title="We could not send the reset link" class="mb-6">
                {{ $errors->first() }}
            </x-ui.alert>
        @endif

        <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf

            <x-ui.input
                id="forgot-email"
                name="email"
                type="email"
                label="Your email"
                :value="old('email')"
                placeholder="name@company.com"
                autocomplete="email"
                required
            />

            <x-ui.button type="submit" class="w-full">Send reset link</x-ui.button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Back to sign in</a>
            </p>
        </form>
    </x-auth.panel>
</x-layouts.auth>
