#!/usr/bin/env bash

echo "=== HOMYGO EMERGENCY BUILD SCRIPT ==="
echo "Build started at: $(date)"

# Set error handling
set -e

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
echo "Generating application key..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
    echo "APP_KEY generated"
else
    echo "APP_KEY already set"
fi

# Use emergency routes
echo "Switching to emergency routes..."
cp routes/emergency.php routes/web.php

# Cache clear and optimize for production
echo "Optimizing Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database connection: SUCCESS';
} catch (Exception \$e) {
    echo 'Database connection: FAILED - ' . \$e->getMessage();
    exit(1);
}
"

echo "Emergency build completed successfully!"
echo "Build finished at: $(date)"
