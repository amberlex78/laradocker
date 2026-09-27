<x-layouts.auth title="Sign in">
    <x-auth.panel title="Log in to your account" description="Use your {{ config('app.name', 'Laravel') }} credentials to continue.">
        @if ($errors->any())
            <x-ui.alert variant="danger" title="Unable to log in" class="mb-6">
                <ul class="flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

        <form class="space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <x-ui.input
                    id="login-email"
                    name="email"
                    type="email"
                    label="Your email"
                    :value="old('email')"
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
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 bg-gray-50 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
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
