#!/usr/bin/env bash

# Render.com Build Script for HomyGo Laravel Application
# This script handles the deployment process on Render

echo "🚀 Starting HomyGo deployment on Render..."

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
echo "🎨 Building frontend assets..."
npm ci
npm run build

# Generate application key if not exists
echo "🔑 Checking application key..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create storage directories
echo "📁 Setting up storage directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Clear and cache configurations for production
echo "⚡ Optimizing for production..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed initial data if needed
echo "🌱 Seeding database..."
php artisan db:seed --force

# Cache configurations for better performance
echo "📊 Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

echo "✅ HomyGo deployment completed successfully!"
echo "🌐 Your application should be available at: $APP_URL"
