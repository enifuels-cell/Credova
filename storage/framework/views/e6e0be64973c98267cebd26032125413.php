<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Homygo | Find Your Perfect Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background-color: #f8f9fa;
    }
    
    .hero-bg {
      background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    
    .search-shadow {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .card-shadow {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

  <!-- Header -->
  <header class="bg-white/95 backdrop-blur-sm shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="flex items-center justify-between px-4 py-3">
      <!-- Logo -->
      <div class="flex items-center">
        <img src="<?php echo e(asset('header.svg')); ?>" alt="Homygo" class="h-8" />
      </div>
      
      <!-- Menu Icon -->
      <div class="relative">
        <button id="menuButton" class="p-2">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        
        <!-- Dropdown Menu -->
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
          <?php if(auth()->guard()->check()): ?>
            <div class="px-4 py-2 border-b">
              <p class="text-sm font-medium text-gray-900"><?php echo e(Auth::user()->name); ?></p>
              <p class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></p>
            </div>
            <a href="<?php echo e(route('properties.index')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Browse Properties</a>
            <a href="<?php echo e(route('renter.bookings')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Bookings</a>
            <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
            <div class="border-t mt-2 pt-2">
              <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                  Logout
                </button>
              </form>
            </div>
          <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
            <a href="<?php echo e(route('register')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero-bg h-96 mt-16 flex flex-col justify-center items-center text-center px-4">
    <h2 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
      Find Your Perfect<br>Home
    </h2>
    <p class="text-lg text-white/90 mb-6">
      Explore the best rentals in Cagayan de Oro
    </p>
  </section>

  <!-- Search Box -->
  <div class="px-4 -mt-8 relative z-10">
    <form action="<?php echo e(route('properties.index')); ?>" method="GET" class="bg-white rounded-lg search-shadow p-2 max-w-md mx-auto">
      <div class="flex items-center">
        <input 
          type="text" 
          name="search"
          placeholder="Search by location, landmark, or barangay"
          class="flex-1 px-4 py-4 text-gray-700 placeholder-gray-400 bg-transparent outline-none text-sm"
          value="<?php echo e(request('search')); ?>"
        />
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg transition-colors duration-200">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </button>
      </div>
    </form>
  </div>

  <!-- Popular Rentals Section -->
  <section class="px-4 py-8">
    <h3 class="text-2xl font-bold text-gray-800 text-center mb-6">
      Explore Popular Rentals in CDO
    </h3>
    
    <div class="space-y-4 max-w-md mx-auto">
      <?php if(isset($featuredProperties) && $featuredProperties->count() > 0): ?>
        <?php $__currentLoopData = $featuredProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <!-- Property Card -->
          <div class="bg-white rounded-lg card-shadow overflow-hidden">
            <img 
              src="<?php echo e($property->image ? asset('storage/'.$property->image) : 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); ?>" 
              alt="<?php echo e($property->title); ?>" 
              class="w-full h-48 object-cover"
            />
            <div class="p-4">
              <h4 class="text-lg font-semibold text-gray-800 mb-2">
                <?php echo e($property->title); ?>

              </h4>
              <p class="text-gray-600 text-sm mb-3">
                <?php echo e(Str::limit($property->description, 50)); ?> • <?php echo e($property->location); ?>

              </p>
              <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-gray-900">₱<?php echo e(number_format($property->price_per_night)); ?></span>
                <span class="text-gray-500 text-sm">/night</span>
              </div>
              <a href="<?php echo e(route('properties.show', $property->id)); ?>" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
                View Details
              </a>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php else: ?>
        <!-- Fallback Mock Data -->
        <!-- Featured Rental Card -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
            alt="Modern Room" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">
              Modern Studio in Downtown CDO
            </h4>
            <p class="text-gray-600 text-sm mb-3">
              Fully furnished • WiFi • Near IT Park
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xl font-bold text-gray-900">₱25,000</span>
              <span class="text-gray-500 text-sm">/month</span>
            </div>
            <a href="<?php echo e(route('properties.index')); ?>" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
              View Details
            </a>
          </div>
        </div>

        <!-- Second Rental Card -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
            alt="Cozy Apartment" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">
              Cozy 1BR near Centrio Mall
            </h4>
            <p class="text-gray-600 text-sm mb-3">
              1 Bedroom • Parking • Shopping nearby
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xl font-bold text-gray-900">₱18,000</span>
              <span class="text-gray-500 text-sm">/month</span>
            </div>
            <a href="<?php echo e(route('properties.index')); ?>" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
              View Details
            </a>
          </div>
        </div>

        <!-- Third Rental Card -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
          <img 
            src="https://images.unsplash.com/photo-1484154218962-a197022b5858?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
            alt="Family House" 
            class="w-full h-48 object-cover"
          />
          <div class="p-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-2">
              Family House in Pueblo de Oro
            </h4>
            <p class="text-gray-600 text-sm mb-3">
              3 Bedrooms • Garden • Gated community
            </p>
            <div class="flex items-center justify-between">
              <span class="text-xl font-bold text-gray-900">₱45,000</span>
              <span class="text-gray-500 text-sm">/month</span>
            </div>
            <a href="<?php echo e(route('properties.index')); ?>" class="block w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
              View Details
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- View All Button -->
    <div class="text-center mt-8">
      <a href="<?php echo e(route('properties.index')); ?>" class="inline-block bg-white text-green-600 border-2 border-green-600 hover:bg-green-600 hover:text-white px-8 py-3 rounded-lg font-medium transition-colors duration-200">
        View All Properties
      </a>
    </div>
  </section>

  <!-- Bottom Spacing -->
  <div class="h-8"></div>

  <script>
    // Dropdown menu functionality
    document.addEventListener('DOMContentLoaded', function() {
      const menuButton = document.getElementById('menuButton');
      const dropdownMenu = document.getElementById('dropdownMenu');
      
      menuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('hidden');
      });
      
      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!menuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
          dropdownMenu.classList.add('hidden');
        }
      });
    });
  </script>

</body>
</html>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/renter/dashboard.blade.php ENDPATH**/ ?>