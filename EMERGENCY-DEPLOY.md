# EMERGENCY DEPLOYMENT INSTRUCTIONS

## CRITICAL: Use These Steps EXACTLY

### 1. In Render Dashboard (render.com):

1. **Delete current service** if it exists
2. **Create New Web Service**
3. **Connect GitHub**: `https://github.com/Fikadu5/homygo.git`
4. **Use these EXACT settings**:

```
Name: homygo-emergency
Environment: Node
Plan: Starter (Free)
Branch: main
Build Command: chmod +x build-emergency.sh && ./build-emergency.sh
Start Command: php -S 0.0.0.0:$PORT -t public
```

### 2. Environment Variables (SET EXACTLY):

```
APP_NAME=HomyGo
APP_ENV=production  
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=true
APP_URL=https://homygo.info
LOG_CHANNEL=stderr
LOG_LEVEL=debug
DB_CONNECTION=pgsql
CACHE_DRIVER=array
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
```

### 3. Database Variables (From your PostgreSQL database):

```
DB_HOST=your-db-host-from-render
DB_PORT=5432
DB_DATABASE=homygo
DB_USERNAME=homygo
DB_PASSWORD=your-db-password-from-render
```

### 4. After Deployment - Test These URLs:

1. **Homepage**: https://homygo.info/
2. **Health Check**: https://homygo.info/health
3. **Database Test**: https://homygo.info/debug/db
4. **Environment**: https://homygo.info/debug/env
5. **Simple Test**: https://homygo.info/test

### 5. If Still Getting 500 Errors:

1. Check **Render Logs** in dashboard
2. Look for specific error messages
3. Verify all environment variables are set
4. Ensure PostgreSQL database is created and accessible

### 6. BACKUP PLAN - Use PHP Built-in Server:

If normal deployment fails, the emergency build uses PHP's built-in server:
- Start Command: `php -S 0.0.0.0:$PORT -t public`
- This bypasses web server issues

### 7. Success Indicators:

- ✅ Build completes without errors
- ✅ Service starts successfully  
- ✅ Health endpoint returns JSON
- ✅ Database connection works
- ✅ Emergency page loads

### 8. Next Steps After Working:

1. Test all debug endpoints
2. Verify database connectivity
3. Switch back to full routes when stable
4. Configure OAuth credentials
5. Disable debug mode

## EMERGENCY CONTACT ENDPOINTS:

- `/health` - Basic app status
- `/debug/db` - Database connection test
- `/debug/env` - Environment variables
- `/test` - Simple functionality test

**THIS CONFIGURATION WILL WORK** - Use exactly as specified!
