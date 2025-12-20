# HomyGO Deployment Guide for Render

This guide will help you deploy your Laravel HomyGO application to Render.

## Prerequisites

1. Push your code to a Git repository (GitHub, GitLab, or Bitbucket)
2. Have a Render account (sign up at https://render.com)

## Deployment Steps

### Option 1: Using render.yaml (Recommended)

1. **Push your code to Git repository**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Connect to Render**
   - Go to https://render.com/dashboard
   - Click "New +" → "Blueprint"
   - Connect your Git repository
   - Render will automatically detect the `render.yaml` file

3. **Configure Environment Variables**
   The following environment variables will be automatically set from render.yaml:
   - `APP_KEY` (auto-generated)
   - `APP_URL` (auto-set from service URL)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `DB_CONNECTION=sqlite`
   - And others...

4. **Deploy**
   - Click "Apply" to start the deployment
   - Wait for the build and deployment process to complete

### Option 2: Manual Web Service Creation

1. **Create a Web Service**
   - Go to Render Dashboard
   - Click "New +" → "Web Service"
   - Connect your repository

2. **Configure Build & Deploy Settings**
   - **Environment**: PHP
   - **Build Command**: `./build.sh`
   - **Start Command**: `./start.sh`
   - **Auto-Deploy**: Yes

3. **Set Environment Variables**
   Go to the Environment tab and add:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:YOUR_GENERATED_KEY
   APP_URL=https://your-service-url.onrender.com
   LOG_CHANNEL=stderr
   LOG_LEVEL=error
   DB_CONNECTION=sqlite
   DB_DATABASE=/opt/render/project/src/database/database.sqlite
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   MAIL_MAILER=log
   ```

4. **Deploy**
   - Click "Create Web Service"
   - Wait for deployment to complete

## Important Notes

### Port Binding Fix
The deployment scripts automatically bind to `0.0.0.0:$PORT` which fixes the port binding issue you encountered.

### Database
- Uses SQLite by default for simplicity
- Database file is created automatically during deployment
- Migrations run automatically on each deployment

### File Storage
- Uses local storage by default
- For persistent file storage, consider using Render Disks or external storage like AWS S3

### Environment Variables
- `APP_KEY` will be auto-generated if not provided
- `APP_URL` will be automatically set to your Render service URL
- Logs are configured to output to stderr for Render's log aggregation

### SSL/HTTPS
- Render automatically provides SSL certificates
- Your app will be available via HTTPS

## Troubleshooting

### Build Failures
1. Check the build logs in Render dashboard
2. Ensure all dependencies are properly listed in composer.json and package.json
3. Verify file permissions

### Runtime Issues
1. Check application logs in Render dashboard
2. Verify environment variables are set correctly
3. Check database connectivity

### Database Issues
1. Ensure SQLite file permissions are correct
2. Check if migrations are running properly
3. Verify database path in environment variables

## Post-Deployment

1. **Test your application**: Visit your Render URL
2. **Set up custom domain** (if needed): Configure in Render dashboard
3. **Configure monitoring**: Set up health checks and alerts
4. **Backup strategy**: Plan for database backups if using SQLite

## Scaling Considerations

For production use, consider:
- Upgrading to a higher Render plan
- Using PostgreSQL instead of SQLite
- Implementing Redis for caching and sessions
- Setting up a separate queue worker service
- Using external file storage (AWS S3, Cloudinary, etc.)

## Support

If you encounter issues:
1. Check Render documentation: https://render.com/docs
2. Review Laravel deployment best practices
3. Check application logs for specific error messages
