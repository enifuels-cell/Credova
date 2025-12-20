#!/bin/bash

# Build script for Render deployment
set -e

echo "Starting build process..."

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies
echo "Installing Node dependencies..."
npm ci

# Build assets
echo "Building assets..."
npm run build

# Create necessary directories
echo "Creating directories..."
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p bootstrap/cache

# Create SQLite database file
echo "Creating SQLite database..."
touch database/database.sqlite

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 664 database/database.sqlite

# Generate application key if not set
echo "Generating application key..."
php artisan key:generate --force --no-interaction

# Clear all caches first
echo "Clearing caches..."
php artisan config:clear --no-interaction
php artisan route:clear --no-interaction
php artisan view:clear --no-interaction
php artisan cache:clear --no-interaction

# Cache configuration
echo "Caching configuration..."
php artisan config:cache

# Cache routes
echo "Caching routes..."
php artisan route:cache

# Cache views
echo "Caching views..."
php artisan view:cache

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Seed the database with roles and basic data
echo "Seeding database..."
php artisan db:seed --force --no-interaction

echo "Build completed successfully!"
