<x-guest-layout>
<<<<<<< HEAD
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Account</h1>
        <p class="text-gray-500 text-sm mt-1">Join HomyGo to find your perfect home</p>
    </div>

=======
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
<<<<<<< HEAD
            <x-input-label for="name" :value="__('Full Name')" class="text-base font-medium" />
            <x-text-input id="name" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Juan Dela Cruz" />
=======
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
<<<<<<< HEAD
        <div class="mt-5">
            <x-input-label for="email" :value="__('Email Address')" class="text-base font-medium" />
            <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
=======
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
<<<<<<< HEAD
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-base font-medium" />

            <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="••••••••" />
=======
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
<<<<<<< HEAD
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-base font-medium" />

            <x-text-input id="password_confirmation" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />
=======
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

<<<<<<< HEAD
        <div class="mt-6">
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-4 px-6 rounded-xl transition text-base touch-target shadow-sm">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Sign in</a>
        </p>
    </div>
=======
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
</x-guest-layout>
