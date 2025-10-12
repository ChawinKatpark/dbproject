<section>
    <header>
        <h3 class="text-xl font-semibold text-[#2e4238] mb-1">Profile Password</h3>

        <p class="mt-1 text-sm text-black">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-black mb-1">Current Password</label>
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full bg-[#1b1b18] text-white rounded-md px-4 py-2 focus:ring-[#5c7266]"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600" />
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-black mb-1">New Password</label>
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full bg-[#1b1b18] text-white rounded-md px-4 py-2 focus:ring-[#5c7266]"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-black mb-1">Confirm Password</label>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full bg-[#1b1b18] text-white rounded-md px-4 py-2 focus:ring-[#5c7266]"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <!-- Save Button -->
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-[#1b1b18] text-white px-4 py-2 rounded hover:bg-[#333] transition">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
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