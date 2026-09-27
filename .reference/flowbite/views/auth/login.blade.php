<x-layouts.auth title="Sign in">
    <x-auth.panel title="Sign in to your account" description="Welcome back. Enter your details to continue.">
        <form class="space-y-6" action="#" method="post">
            <div class="space-y-5">
                <x-ui.input
                    id="login-email"
                    name="email"
                    type="email"
                    label="Your email"
                    placeholder="name@company.com"
                    autocomplete="email"
                    required
                />

                <x-ui.input
                    id="login-password"
                    name="password"
                    type="password"
                    label="Your password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 bg-gray-50 text-blue-600 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Forgot password?</a>
            </div>

            <x-ui.button type="submit" class="w-full">Sign in to account</x-ui.button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                Not registered?
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Create an account</a>
            </p>
        </form>
    </x-auth.panel>
</x-layouts.auth>
