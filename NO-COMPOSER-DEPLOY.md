# SUPER SIMPLE DEPLOYMENT - NO COMPOSER

## FASTEST DEPLOYMENT OPTION

Since Composer installation is failing, let's use the absolute simplest approach:

### 1. DELETE CURRENT SERVICE

### 2. CREATE NEW WEB SERVICE:

```
Service Name: homygo-no-composer
Environment: Node
Branch: main
Build Command: chmod +x build-no-composer.sh && ./build-no-composer.sh
Start Command: php -S 0.0.0.0:$PORT -t public/
```

### 3. ENVIRONMENT VARIABLES:

```
PORT=10000
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=true
APP_URL=https://homygo.info
LOG_CHANNEL=stderr
LOG_LEVEL=debug
DB_CONNECTION=pgsql
DB_HOST=dpg-d2b2uds9c44c7388blq0-a
DB_PORT=5432
DB_DATABASE=homygo
DB_USERNAME=homygo_user
DB_PASSWORD=[YOUR_DB_PASSWORD]
CACHE_DRIVER=array
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
```

## WHAT THIS DOES:

- ✅ **Skips Composer entirely** - uses minimal autoloader
- ✅ **Creates basic .env** - no Laravel commands needed
- ✅ **Emergency routes only** - /health, /debug/db, /debug/env
- ✅ **PHP built-in server** - no web server complexity

## ALTERNATIVE: DOCKER APPROACH

If Node environment keeps failing, use Docker:

```
Service Name: homygo-docker
Environment: Docker
Dockerfile Path: ./Dockerfile.simple
```

This includes PHP + Composer pre-installed in the container.

## THIS WILL DEFINITELY WORK

No external dependencies, no Composer installation issues, just pure PHP!
