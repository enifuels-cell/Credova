<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome Back!</h1>
        <p class="text-gray-500 text-sm mt-1">Sign in to your HomyGo account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-base font-medium" />
            <x-text-input id="email" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-base font-medium" />

            <x-text-input id="password" class="block mt-2 w-full rounded-xl border-gray-300 py-3.5 text-base touch-target focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer touch-target py-1">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-base text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-700 font-medium touch-target py-1" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-4 px-6 rounded-xl transition text-base touch-target shadow-sm">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600 text-sm">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Create one</a>
        </p>
    </div>
</x-guest-layout>
