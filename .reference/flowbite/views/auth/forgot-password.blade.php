<x-layouts.auth title="Forgot password">
    <x-auth.panel title="Forgot your password?" description="Enter your email and we will send you a password reset link.">
        <form class="space-y-6" action="#" method="post">
            <x-ui.input
                id="forgot-email"
                name="email"
                type="email"
                label="Your email"
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
