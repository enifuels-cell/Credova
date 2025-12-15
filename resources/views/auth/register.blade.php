<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Account</h1>
        <p class="text-gray-500 text-sm mt-1">Join HomyGo to find your perfect home</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-base font-medium" />
            <x-text-input id="name" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Juan Dela Cruz" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" :value="__('Email Address')" class="text-base font-medium" />
            <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-base font-medium" />

            <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-base font-medium" />

            <x-text-input id="password_confirmation" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

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
</x-guest-layout>
