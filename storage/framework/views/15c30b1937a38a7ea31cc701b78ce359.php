<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Global API Helper Script -->
        <script>
            // Global API helper functions for HTTPS enforcement
            window.API = {
                baseUrl: <?php echo json_encode(config('app.url'), 15, 512) ?>,
                
                // Check if we're in local development
                isLocal: function() {
                    const host = window.location.hostname;
                    const port = window.location.port;
                    return host === 'localhost' || 
                           host === '127.0.0.1' || 
                           host.includes('.local') ||
                           ['8000', '3000', '5173', '8080'].includes(port);
                },
                
                // Create appropriate URL (respects local development)
                url: function(path) {
                    if (path.startsWith('http')) return path;
                    
                    // In local development, use current protocol/host
                    if (this.isLocal()) {
                        return window.location.origin + (path.startsWith('/') ? path : '/' + path);
                    }
                    
                    // In production, force HTTPS
                    return this.baseUrl + (path.startsWith('/') ? path : '/' + path);
                },
                
                // Get default headers with CSRF token
                headers: function(additionalHeaders = {}) {
                    const headers = {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    };
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                    }
                    
                    return { ...headers, ...additionalHeaders };
                },
                
                // Enhanced fetch wrapper
                fetch: function(url, options = {}) {
                    return fetch(this.url(url), {
                        headers: this.headers(options.headers || {}),
                        ...options
                    });
                }
            };
            
            // Only override global fetch in production environments
            if (!window.API.isLocal() && '<?php echo e(app()->environment()); ?>' === 'production') {
                const originalFetch = window.fetch;
                window.fetch = function(url, options = {}) {
                    if (typeof url === 'string' && url.startsWith('/')) {
                        return originalFetch(window.API.url(url), options);
                    }
                    return originalFetch(url, options);
                };
            }
        </script>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <!-- HomyGo Logo -->
            <div class="flex justify-center mt-8">
                <a href="/">
                    <img src="<?php echo e(asset('H.svg')); ?>" alt="Homygo Logo" class="w-16 h-16 hover:scale-110 transition-transform duration-300">
                </a>
            </div>

            <!-- Login card below -->
            <div class="bg-white rounded-lg shadow-md p-6 max-w-sm mx-auto mt-4 w-full sm:max-w-md">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\Users\Administrator\homygo\resources\views/layouts/guest.blade.php ENDPATH**/ ?>