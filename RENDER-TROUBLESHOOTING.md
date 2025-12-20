# HomyGo Render Deployment Troubleshooting Guide

## 🚨 Current Issue: 500 Error on Render Deployment

### Common Causes & Solutions:

## 1. Environment Configuration Issues

### ✅ Fix: Update Render Environment Variables
In your Render dashboard, add these environment variables:

```
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://homygo-2025-k0nl.onrender.com
APP_KEY=base64:TtnljvZvTe5cQwUqZzeSjD4VWNi/JUSucZfGd2xEIho=

# Database (Render provides these automatically)
DB_CONNECTION=pgsql
DB_HOST=${POSTGRES_HOST}
DB_PORT=${POSTGRES_PORT}
DB_DATABASE=${POSTGRES_DB}
DB_USERNAME=${POSTGRES_USER}
DB_PASSWORD=${POSTGRES_PASSWORD}

# Cache & Sessions
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Logging
LOG_LEVEL=error
LOG_CHANNEL=stack
```

## 2. Build Process Issues

### ✅ Fix: Update Build Command in Render
Use this build command:
```bash
composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### ✅ Fix: Update Start Command
Use this start command:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

## 3. Database Migration Issues

### ✅ Fix: Manual Migration Command
If migrations fail during build, run them manually:
1. Access Render shell
2. Run: `php artisan migrate:fresh --force`
3. Run: `php artisan db:seed --force`

## 4. Asset Build Issues

### ✅ Fix: Simplified Asset Build
If Vite fails, temporarily disable asset compilation:
1. Remove `@vite` directives from views
2. Use inline CSS instead
3. We've created `welcome-simple.blade.php` for this

## 5. Storage Permission Issues

### ✅ Fix: Storage Setup
Run these commands in Render shell:
```bash
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan storage:link
```

## 6. Database Connection Issues

### ✅ Fix: PostgreSQL Configuration
Ensure your `config/database.php` has correct PostgreSQL settings:
- Port: 5432 (not 3306)
- Charset: utf8 (not utf8mb4)
- SSL mode: prefer

## 🔧 Quick Debug Steps:

### Step 1: Check Logs
In Render dashboard, check:
- Build logs for errors
- Runtime logs for PHP errors

### Step 2: Test Simple Route
We've simplified the homepage to avoid asset issues.

### Step 3: Test Database Connection
Add this debug route temporarily:
```php
Route::get('/debug', function() {
    try {
        DB::connection()->getPdo();
        return 'Database connected successfully!';
    } catch (Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});
```

### Step 4: Check Environment
Add this debug route:
```php
Route::get('/env-debug', function() {
    return [
        'APP_ENV' => env('APP_ENV'),
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'APP_DEBUG' => env('APP_DEBUG'),
        'PHP_VERSION' => phpversion(),
    ];
});
```

## 🚀 Recommended Deploy Process:

1. **Set Environment Variables** in Render dashboard
2. **Update Build Command** to use our optimized script
3. **Add PostgreSQL Database** service in Render
4. **Test with Simplified Homepage** first
5. **Gradually Enable Features** once basic deployment works

## 📞 Next Steps:

1. Apply environment variable fixes in Render dashboard
2. Trigger new deployment
3. Check if simple homepage loads
4. Enable full features once basic site works

The main issue is likely environment configuration or database connection. Follow the environment variable setup first!
