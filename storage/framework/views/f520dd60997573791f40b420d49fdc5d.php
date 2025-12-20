<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Homygo | Landlord Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background-color: #f8f9fa;
    }
    
    .card-shadow {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }
    
    .stat-shadow {
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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
      
      <!-- User Info & Menu -->
      <div class="flex items-center space-x-3">
        <span class="text-sm font-medium text-gray-700"><?php echo e(Auth::user()->name); ?></span>
        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">
          <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

        </div>
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 px-2 py-1 rounded">
            Logout
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="pt-20 px-4 pb-8">
    <div class="max-w-md mx-auto space-y-6">
      
      <!-- Welcome Section -->
      <div class="text-center py-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Landlord Dashboard</h1>
        <p class="text-gray-600">Manage your properties and bookings</p>
      </div>

      <!-- Quick Stats -->
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-lg p-4 stat-shadow">
          <div class="text-center">
            <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['activeListings']); ?></div>
            <div class="text-sm text-gray-600">Active Listings</div>
          </div>
        </div>
        <div class="bg-white rounded-lg p-4 stat-shadow">
          <div class="text-center">
            <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['monthlyIncome']); ?></div>
            <div class="text-sm text-gray-600">Monthly Income</div>
          </div>
        </div>
        <div class="bg-white rounded-lg p-4 stat-shadow">
          <div class="text-center">
            <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['bookedProperties']); ?></div>
            <div class="text-sm text-gray-600">Booked Properties</div>
          </div>
        </div>
        <div class="bg-white rounded-lg p-4 stat-shadow">
          <div class="text-center">
            <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['pendingRequests']); ?></div>
            <div class="text-sm text-gray-600">Pending Requests</div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white rounded-lg card-shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
          <a href="<?php echo e(route('properties.create')); ?>" class="block w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
            + Add New Property
          </a>
          <a href="<?php echo e(route('owner.properties')); ?>" class="block w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
            View All Properties
          </a>
          <a href="<?php echo e(route('bookings.index')); ?>" class="block w-full bg-green-800 hover:bg-green-900 text-white py-3 rounded-lg font-medium transition-colors duration-200 text-center">
            Manage Bookings
          </a>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-lg card-shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
        <div class="space-y-3">
          <!-- Activity Item -->
          <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-800">New booking confirmed</p>
              <p class="text-xs text-gray-600">Studio in Downtown CDO • 2 hours ago</p>
            </div>
          </div>

          <!-- Activity Item -->
          <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
            <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-800">Payment received</p>
              <p class="text-xs text-gray-600">₱25,000 from John Doe • 5 hours ago</p>
            </div>
          </div>

          <!-- Activity Item -->
          <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
            <div class="w-8 h-8 bg-green-800 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-800">New review received</p>
              <p class="text-xs text-gray-600">5 stars for BGC Condo • 1 day ago</p>
            </div>
          </div>
        </div>
      </div>

      <!-- My Properties -->
      <div class="bg-white rounded-lg card-shadow p-4">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">My Properties</h3>
          <a href="<?php echo e(route('owner.properties')); ?>" class="text-green-600 text-sm font-medium hover:text-green-700">View All</a>
        </div>
        
        <div class="space-y-3">
          <?php if(isset($recentProperties) && $recentProperties->count() > 0): ?>
            <?php $__currentLoopData = $recentProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <!-- Property Item -->
              <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <img src="<?php echo e($property->image ? asset('storage/'.$property->image) : 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80'); ?>" 
                     alt="<?php echo e($property->title); ?>" class="w-12 h-12 rounded-lg object-cover">
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-800"><?php echo e(Str::limit($property->title, 25)); ?></p>
                  <p class="text-xs text-gray-600">₱<?php echo e(number_format($property->price_per_night)); ?>/night • Available</p>
                </div>
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <!-- No Properties Message -->
            <div class="text-center py-6">
              <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              </svg>
              <p class="text-sm text-gray-500 mb-3">No properties yet</p>
              <a href="<?php echo e(route('properties.create')); ?>" class="text-green-600 hover:text-green-700 text-sm font-medium">
                Add your first property
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Earnings Chart -->
      <div class="bg-white rounded-lg card-shadow p-4">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Earnings</h3>
        <div class="h-48">
          <canvas id="earningsChart"></canvas>
        </div>
      </div>

    </div>
  </main>

  <script>
    // Initialize earnings chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    
    // Generate realistic chart data based on current stats
    const monthlyIncome = <?php echo e($stats['monthlyIncome'] === '₱0' ? 0 : (int) str_replace(['₱', ','], '', $stats['monthlyIncome'])); ?>;
    const baseAmount = monthlyIncome > 0 ? monthlyIncome : 25000;
    
    // Generate 7 months of data with some variation
    const chartData = [];
    for(let i = 6; i >= 0; i--) {
      const variation = (Math.random() - 0.5) * 0.3; // ±15% variation
      const amount = Math.max(0, baseAmount * (1 + variation));
      chartData.push(Math.round(amount));
    }
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Earnings',
          data: chartData,
          borderColor: '#16a34a',
          backgroundColor: 'rgba(22, 163, 74, 0.1)',
          borderWidth: 3,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return '₱' + (value/1000) + 'k';
              }
            }
          }
        }
      }
    });
</body>
</html>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/owner/dashboard.blade.php ENDPATH**/ ?>