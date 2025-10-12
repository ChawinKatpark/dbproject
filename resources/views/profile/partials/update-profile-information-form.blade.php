<section>
    <header>
        <h3 class="text-xl font-semibold text-[#2e4238] mb-1">Profile Information</h3>

        <p class="mt-1 text-sm text-black">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-black mb-1">Name</label>
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full bg-[#1b1b18] text-white rounded-md px-4 py-2 focus:ring-[#5c7266]"
                :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-black mb-1">Email</label>
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full bg-[#1b1b18] text-white rounded-md px-4 py-2 focus:ring-[#5c7266]"
                :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-black">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-[#5c7266] hover:text-black rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5c7266]">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#1b1b18] text-white px-4 py-2 rounded hover:bg-[#333] transition">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>