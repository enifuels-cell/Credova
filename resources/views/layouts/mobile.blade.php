<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Homygo - Property Rental Platform')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

  <!-- Header -->
  <header class="header-blur shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center justify-between px-4 py-3">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="@yield('home-route', route('dashboard'))">
          <img src="{{ asset('header.svg') }}" alt="Homygo" class="h-8" />
        </a>
      </div>
      
      <!-- User Info & Menu -->
      <div class="flex items-center space-x-3">
        @auth
          <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
          <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">
            {{ substr(Auth::user()->name, 0, 1) }}
          </div>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="nav-link px-2 py-1 rounded">
              Logout
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="nav-link">Login</a>
          <a href="{{ route('register') }}" class="btn-primary">Register</a>
        @endauth
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="pt-20 section-padding">
    <div class="container-max space-y-6 fade-in">
      
      @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center slide-up">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center slide-up">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          {{ session('error') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg slide-up">
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')

    </div>
  </main>

  @stack('scripts')

</body>
</html>
