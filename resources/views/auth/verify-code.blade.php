<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by entering the code we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="request()->email ?? old('email')" required autofocus />
            </div>
            
            <div class="mt-4">
                <x-label for="verification_code" value="{{ __('Verification Code') }}" />
                <x-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Verify') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
