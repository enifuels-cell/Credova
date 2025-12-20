#!/usr/bin/env bash

echo "=== HOMYGO ULTRA-SIMPLE BUILD ==="
echo "Build started at: $(date)"

# Set error handling
set -e

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
echo "Generating application key..."
php artisan key:generate --force --no-interaction

# Use emergency routes
echo "Switching to emergency routes..."
cp routes/emergency.php routes/web.php

# Clear all caches
echo "Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

echo "Build completed successfully!"
echo "Starting PHP server on port $PORT"
