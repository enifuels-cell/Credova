# 🚨 RENDER 500 ERROR HOTFIX

## Issue: 500 Error on Render Deployment
The error occurs during application termination, indicating environment/configuration issues.

## 🔧 IMMEDIATE FIX

### Step 1: Update Environment Variables in Render Dashboard

**CRITICAL:** Add these exact environment variables in your Render service:

```bash
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://homygo.info
APP_KEY=base64:TtnljvZvTe5cQwUqZzeSjD4VWNi/JUSucZfGd2xEIho=

# Database (Render auto-provides these when you add PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${POSTGRES_HOST}
DB_PORT=${POSTGRES_PORT}
DB_DATABASE=${POSTGRES_DB}
DB_USERNAME=${POSTGRES_USER}
DB_PASSWORD=${POSTGRES_PASSWORD}

# Cache & Sessions
CACHE_DRIVER=array
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Logging (Critical for debugging)
LOG_LEVEL=debug
LOG_CHANNEL=stderr

# Mail
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@homygo.info
MAIL_FROM_NAME=HomyGo

# Broadcasting
BROADCAST_DRIVER=log
```

### Step 2: Update Build Command
In Render service settings, use this exact build command:

```bash
composer install --no-dev --optimize-autoloader --no-interaction && npm ci && npm run build && mkdir -p storage/framework/{cache,sessions,views} && mkdir -p storage/logs && chmod -R 775 storage && php artisan key:generate --force && php artisan migrate --force --no-interaction && php artisan config:cache && php artisan route:cache
```

### Step 3: Update Start Command
Use this exact start command:

```bash
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
```

### Step 4: Add PostgreSQL Database
1. In Render dashboard: New → PostgreSQL
2. Name: `homygo-postgres`
3. Link it to your web service

## 🔍 DEBUGGING STEPS

### Test These URLs After Deployment:
1. `https://homygo.info/debug/health` - Basic health check
2. `https://homygo.info/debug/env` - Environment variables
3. `https://homygo.info/debug/db` - Database connection

### If Still Getting 500 Error:
1. Check Render logs for specific error messages
2. Set `APP_DEBUG=true` temporarily to see detailed errors
3. Ensure PostgreSQL database is properly connected

## 🚀 ALTERNATIVE: SIMPLIFIED DEPLOYMENT

If above doesn't work, use this minimal configuration:

**Build Command:**
```bash
composer install --no-dev && npm run build
```

**Start Command:**
```bash
php -S 0.0.0.0:$PORT public/index.php
```

**Environment Variables:**
```bash
APP_ENV=production
APP_DEBUG=true
APP_KEY=base64:TtnljvZvTe5cQwUqZzeSjD4VWNi/JUSucZfGd2xEIho=
DB_CONNECTION=sqlite
CACHE_DRIVER=array
SESSION_DRIVER=file
```

## 📞 EMERGENCY CONTACT

If none of these work:
1. Check Render build logs
2. Check Render runtime logs  
3. Enable debug mode temporarily
4. Test locally first with `php artisan serve`

Your HomyGo platform is solid - this is just a deployment configuration issue! 💪
