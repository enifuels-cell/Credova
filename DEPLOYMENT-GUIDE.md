# 🚀 HomyGo GitHub & Render Deployment Guide

## Step 1: Push to GitHub

### 1.1 Initialize Git (if not already done)
```bash
cd c:\Users\Administrator\homygo
git init
git add .
git commit -m "Initial commit: HomyGo property rental platform"
```

### 1.2 Add GitHub Remote and Push
```bash
git remote add origin https://github.com/Homygo25/HomyGO-2025.git
git branch -M main
git push -u origin main
```

## Step 2: Deploy to Render

### 2.1 Create Render Account
1. Go to [render.com](https://render.com)
2. Sign up or log in
3. Connect your GitHub account

### 2.2 Create New Web Service
1. Click "New +" → "Web Service"
2. Connect repository: `https://github.com/Homygo25/HomyGO-2025`
3. Configure settings:

**Basic Settings:**
- **Name**: `homygo`
- **Region**: Choose closest to your users
- **Branch**: `main`
- **Runtime**: `Node`

**Build & Deploy:**
- **Build Command**:
```bash
composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan migrate --force && php artisan config:cache
```

- **Start Command**:
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

### 2.3 Add Database
1. In Render dashboard, click "New +" → "PostgreSQL"
2. Name: `homygo-postgres`
3. Database Name: `homygo`
4. User: `homygo_user`
5. Copy the database connection details

### 2.4 Environment Variables
In your web service settings, add these environment variables:

```bash
# Application
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.onrender.com
APP_KEY=base64:TtnljvZvTe5cQwUqZzeSjD4VWNi/JUSucZfGd2xEIho=

# Database (Render auto-provides these)
DB_CONNECTION=pgsql
DB_HOST=<from_postgres_service>
DB_PORT=<from_postgres_service>
DB_DATABASE=<from_postgres_service>
DB_USERNAME=<from_postgres_service>
DB_PASSWORD=<from_postgres_service>

# Cache & Sessions
CACHE_DRIVER=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Logging
LOG_LEVEL=error
LOG_CHANNEL=stack

# Mail
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@homygo.com
MAIL_FROM_NAME=HomyGo

# Social Auth (Optional - configure later)
FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URL=${APP_URL}/auth/facebook/callback

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL=${APP_URL}/auth/google/callback
```

### 2.5 Deploy
1. Click "Create Web Service"
2. Render will automatically build and deploy
3. Monitor the build logs for any errors

## Step 3: Post-Deployment Setup

### 3.1 Verify Deployment
Visit these URLs to test:
- `https://your-app.onrender.com/` - Homepage
- `https://your-app.onrender.com/debug/health` - Health check
- `https://your-app.onrender.com/debug/db` - Database connection

### 3.2 Create Admin User
Access the Render shell and run:
```bash
php artisan tinker
User::factory()->create(['email' => 'admin@homygo.com', 'name' => 'Admin'])->assignRole('admin');
```

### 3.3 Configure Social Authentication (Optional)
1. Create Facebook App at [developers.facebook.com](https://developers.facebook.com)
2. Create Google App at [console.developers.google.com](https://console.developers.google.com)
3. Update environment variables with OAuth credentials

## Step 4: Troubleshooting

### Common Issues:

**Build Fails:**
- Check build logs in Render dashboard
- Ensure all dependencies are in composer.json and package.json

**500 Error:**
- Check environment variables are set correctly
- Verify database connection
- Check application logs in Render

**Database Issues:**
- Ensure PostgreSQL service is running
- Verify database environment variables
- Run migrations manually if needed

**Assets Not Loading:**
- Ensure `npm run build` completed successfully
- Check public directory permissions

### Debug Commands:
```bash
# Check environment
curl https://your-app.onrender.com/debug/env

# Check database
curl https://your-app.onrender.com/debug/db

# Check health
curl https://your-app.onrender.com/debug/health
```

## Step 5: Going Live

### 5.1 Custom Domain (Optional)
1. In Render dashboard, go to Settings → Custom Domains
2. Add your domain
3. Update DNS records as instructed

### 5.2 Security Hardening
- Set `APP_DEBUG=false`
- Configure proper `APP_URL`
- Set up SSL (automatic with Render)
- Configure rate limiting appropriately

### 5.3 Performance Optimization
- Enable caching (`config:cache`, `route:cache`, `view:cache`)
- Set up Redis for better caching (optional)
- Configure CDN for static assets (optional)

## 🎉 Congratulations!

Your HomyGo platform should now be live at `https://your-app.onrender.com`!

## 📞 Support

If you encounter issues:
1. Check Render build and runtime logs
2. Review environment variables
3. Test debug endpoints
4. Check GitHub Actions (if configured)

---

**Ready to serve the Filipino property rental market! 🏠🚀**
