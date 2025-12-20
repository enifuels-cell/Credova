<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - HomyGo</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Preload critical assets -->
  <link rel="preload" href="{{ asset('H.svg') }}" as="image">
  
  <!-- CSS - Load synchronously for immediate styling -->
  @vite(['resources/css/app.css'])
  
  <!-- Loading styles for better UX -->
  <style>
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(249, 250, 251, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.3s ease;
    }
    .loading-overlay.hidden {
      opacity: 0;
      pointer-events: none;
    }
    .login-form.disabled {
      pointer-events: none;
      opacity: 0.7;
    }
    .api-status {
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 12px;
      margin-bottom: 16px;
      transition: all 0.3s ease;
    }
    .api-status.checking {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fcd34d;
    }
    .api-status.ready {
      background: #d1fae5;
      color: #065f46;
      border: 1px solid #10b981;
    }
    .api-status.error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #ef4444;
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 bg-gray-50">
  
  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="loading-overlay">
    <div class="text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-4"></div>
      <p class="text-gray-600">Initializing secure login...</p>
    </div>
  </div>

  <div class="w-full max-w-sm dashboard-card">
    
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <a href="{{ url('/') }}">
        <img src="{{ asset('H.svg') }}" alt="HomyGo Logo" class="h-10 w-10 hover:scale-110 transition-transform duration-300">
      </a>
    </div>

    <!-- API Status Indicator -->
    <div id="apiStatus" class="api-status checking">
      <span id="apiStatusText">🔄 Connecting to secure backend...</span>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5 login-form disabled" id="loginForm">
      @csrf
      
      <!-- Email Field -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input 
          type="email" 
          id="email" 
          name="email" 
          value="{{ old('email') }}"
          required 
          autofocus
          autocomplete="username"
          class="form-input @error('email') border-red-500 @enderror"
        />
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password Field -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required 
          autocomplete="current-password"
          class="form-input @error('password') border-red-500 @enderror"
        />
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="flex items-center">
        <input 
          id="remember_me" 
          type="checkbox" 
          class="rounded border-gray-300 text-gray-600 shadow-sm focus:ring-gray-500" 
          name="remember"
        >
        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
          {{ __('Remember me') }}
        </label>
      </div>

      <!-- Login Button -->
      <button 
        type="submit" 
        class="btn-primary w-full"
        id="loginButton"
        disabled
      >
        <span id="loginButtonText">Preparing secure login...</span>
      </button>
    </form>

    <!-- Social Login Section -->
    <div class="mt-6" id="socialLogin" style="opacity: 0.5; pointer-events: none;">
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-2 gap-3">
        <!-- Facebook Login -->
        <a href="{{ route('auth.social.redirect', 'facebook') }}" 
           class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors social-login-btn"
           id="facebookLogin">
          <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"/>
          </svg>
          <span class="ml-2">Facebook</span>
        </a>

        <!-- Google Login -->
        <a href="{{ route('auth.social.redirect', 'google') }}" 
           class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors social-login-btn"
           id="googleLogin">
          <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          <span class="ml-2">Google</span>
        </a>
      </div>
    </div>

    <!-- Footer Links -->
    <div class="mt-6 space-y-2">
      @if (Route::has('password.request'))
        <p class="text-xs text-gray-500 text-center">
          <a href="{{ route('password.request') }}" class="underline hover:text-black transition">
            Forgot your password?
          </a>
        </p>
      @endif
      
      @if (Route::has('register'))
        <p class="text-xs text-gray-500 text-center">
          Don't have an account?
          <a href="{{ route('register') }}" class="underline hover:text-black transition">Register</a>
        </p>
      @endif
    </div>
  </div>

  <!-- JavaScript for Backend API Verification -->
  <script>
    // Backend API Verification System
    class LoginDependencyManager {
      constructor() {
        this.apiStatus = document.getElementById('apiStatus');
        this.apiStatusText = document.getElementById('apiStatusText');
        this.loginForm = document.getElementById('loginForm');
        this.loginButton = document.getElementById('loginButton');
        this.loginButtonText = document.getElementById('loginButtonText');
        this.socialLogin = document.getElementById('socialLogin');
        this.loadingOverlay = document.getElementById('loadingOverlay');
        
        this.init();
      }

      async init() {
        try {
          // Step 1: Check CSRF token
          await this.validateCSRF();
          
          // Step 2: Check backend API health
          await this.checkBackendAPI();
          
          // Step 3: Verify authentication endpoints
          await this.verifyAuthEndpoints();
          
          // Step 4: Enable login form
          this.enableLogin();
          
        } catch (error) {
          console.error('Login initialization failed:', error);
          this.showError(error.message);
        }
      }

      async validateCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token || !token.content) {
          throw new Error('CSRF token not found');
        }
        
        // Set axios defaults if available
        if (window.axios) {
          window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
      }

      async checkBackendAPI() {
        this.updateStatus('checking', '🔄 Verifying backend connection...');
        
        try {
          const response = await fetch('{{ route('health-check') }}', {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: 5000
          });

          if (!response.ok) {
            throw new Error(`Backend API not ready (${response.status})`);
          }

          const data = await response.json();
          if (data.status !== 'ok') {
            throw new Error('Backend API health check failed');
          }

          console.log('✅ Backend API ready:', data);
        } catch (error) {
          throw new Error(`Backend connection failed: ${error.message}`);
        }
      }

      async verifyAuthEndpoints() {
        this.updateStatus('checking', '🔄 Checking authentication system...');
        
        // Check if login route exists
        try {
          const response = await fetch('{{ route('login') }}', {
            method: 'HEAD',
            headers: {
              'Accept': 'text/html,application/xhtml+xml',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (!response.ok && response.status !== 405) {
            throw new Error('Login endpoint not accessible');
          }
        } catch (error) {
          throw new Error(`Authentication system verification failed: ${error.message}`);
        }
      }

      enableLogin() {
        this.updateStatus('ready', '✅ Secure login ready');
        
        // Enable form
        this.loginForm.classList.remove('disabled');
        this.loginButton.disabled = false;
        this.loginButtonText.textContent = 'Log In';
        
        // Enable social login
        this.socialLogin.style.opacity = '1';
        this.socialLogin.style.pointerEvents = 'auto';
        
        // Hide loading overlay
        setTimeout(() => {
          this.loadingOverlay.classList.add('hidden');
        }, 300);

        // Add form validation
        this.addFormValidation();
      }

      showError(message) {
        this.updateStatus('error', `❌ ${message}`);
        
        // Show retry option
        const retryBtn = document.createElement('button');
        retryBtn.textContent = 'Retry Connection';
        retryBtn.className = 'ml-2 text-xs underline hover:no-underline';
        retryBtn.onclick = () => window.location.reload();
        
        this.apiStatus.appendChild(retryBtn);
        
        // Hide loading overlay
        this.loadingOverlay.classList.add('hidden');
      }

      updateStatus(type, text) {
        this.apiStatus.className = `api-status ${type}`;
        this.apiStatusText.textContent = text;
      }

      addFormValidation() {
        this.loginForm.addEventListener('submit', async (e) => {
          const email = document.getElementById('email').value;
          const password = document.getElementById('password').value;

          if (!email || !password) {
            e.preventDefault();
            alert('Please fill in all required fields');
            return;
          }

          // Show loading state
          this.loginButton.disabled = true;
          this.loginButtonText.textContent = 'Signing In...';
          
          // Re-enable after 5 seconds if form doesn't submit
          setTimeout(() => {
            this.loginButton.disabled = false;
            this.loginButtonText.textContent = 'Log In';
          }, 5000);
        });

        // Add real-time validation
        const inputs = this.loginForm.querySelectorAll('input[required]');
        inputs.forEach(input => {
          input.addEventListener('blur', () => {
            if (input.value.trim() === '') {
              input.classList.add('border-red-500');
            } else {
              input.classList.remove('border-red-500');
            }
          });
        });
      }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        new LoginDependencyManager();
      });
    } else {
      new LoginDependencyManager();
    }

    // Load additional assets after API verification
    window.addEventListener('load', () => {
      // Load JavaScript assets asynchronously after everything is ready
      if (document.querySelector('.api-status.ready')) {
        const script = document.createElement('script');
        script.src = '{{ Vite::asset('resources/js/app.js') }}';
        script.async = true;
        document.head.appendChild(script);
      }
    });
  </script>
</body>
</html>
>>>>>>> 6075dc1d35bc5a883e927973514793602300912f
