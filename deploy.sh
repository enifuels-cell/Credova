#!/bin/bash

# Simple deployment script for Render
echo "=== HomyGO Deployment Starting ==="

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build

# Setup database
echo "Setting up database..."
touch database/database.sqlite
chmod 664 database/database.sqlite

# Generate app key and cache
echo "Configuring application..."
php artisan key:generate --force --no-interaction
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database migrations and seeding
echo "Running migrations..."
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

echo "=== Build Complete ==="
