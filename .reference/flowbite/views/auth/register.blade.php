<x-layouts.auth title="Create account">
    <x-auth.panel title="Create your account" description="Start building with the application workspace.">
        <form class="space-y-6" action="#" method="post">
            <div class="space-y-5">
                <x-ui.input
                    id="register-name"
                    name="name"
                    label="Full name"
                    placeholder="Bonnie Green"
                    autocomplete="name"
                    required
                />

                <x-ui.input
                    id="register-email"
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="name@company.com"
                    autocomplete="email"
                    required
                />

                <x-ui.input
                    id="register-password"
                    name="password"
                    type="password"
                    label="Password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />

                <x-ui.input
                    id="register-password-confirmation"
                    name="password_confirmation"
                    type="password"
                    label="Confirm password"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                />
            </div>

            <label class="flex items-start gap-2 text-sm text-gray-500 dark:text-gray-400">
                <input type="checkbox" name="terms" class="mt-0.5 h-4 w-4 rounded border-gray-300 bg-gray-50 text-blue-600 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600" required>
                <span>I agree to the <a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">terms and conditions</a>.</span>
            </label>

            <x-ui.button type="submit" class="w-full">Create account</x-ui.button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Sign in</a>
            </p>
        </form>
    </x-auth.panel>
</x-layouts.auth>
