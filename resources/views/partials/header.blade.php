<header class="absolute top-0 left-0 z-50 w-full p-4 md:px-8 bg-transparent">
    <div class="flex items-center justify-between mx-auto max-w-7xl">
        <div class="text-xl font-bold text-white tracking-tight">
            <a href="{{ route('welcome') }}">HomyGO</a>
        </div>
        <nav class="hidden md:flex space-x-6 text-white font-medium">
            <a href="{{ route('welcome') }}" class="hover:text-teal-300 transition-colors">Home</a>
            <a href="#" class="hover:text-teal-300 transition-colors">Properties</a>
            <a href="#" class="hover:text-teal-300 transition-colors">Become a Host</a>
            <a href="#" class="hover:text-teal-300 transition-colors">Contact</a>
        </nav>
        <div class="hidden md:flex space-x-4">
            @guest
                <a href="{{ route('login') }}" class="text-white font-medium hover:text-teal-300 transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="bg-white text-gray-900 px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gray-200 transition-colors">Sign Up</a>
            @else
                <div class="relative">
                    <span class="text-white">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-4">
                        @csrf
                        <button type="submit" class="text-white hover:text-teal-300 transition-colors">Logout</button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</header>
