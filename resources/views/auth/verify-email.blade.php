<x-layouts.auth title="Verify email">
    <x-auth.panel title="Verify your email" description="Confirm your email address to keep your account secure.">
        <p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
            We sent a verification link to
            <span class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->email }}</span>.
        </p>

        @if (session('status') === 'verification-link-sent')
            <x-ui.alert variant="success" title="Verification link sent" class="mt-6">
                A new verification link has been sent to your email address.
            </x-ui.alert>
        @endif

        @if ($errors->any())
            <x-ui.alert variant="danger" title="We could not send the verification link" class="mt-6">
                {{ $errors->first() }}
            </x-ui.alert>
        @endif

        <div class="mt-6 flex flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-ui.button type="submit" class="w-full">Resend verification email</x-ui.button>
            </form>

            <form class="text-center" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm font-medium text-gray-500 hover:underline dark:text-gray-400" type="submit">Log out</button>
            </form>
        </div>
    </x-auth.panel>
</x-layouts.auth>
