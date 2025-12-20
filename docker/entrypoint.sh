#!/bin/bash

# Set environment variables with defaults
export DB_CONNECTION=${DB_CONNECTION:-sqlite}
export DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export APP_KEY=${APP_KEY}
export APP_URL=${APP_URL}

# Wait for database to be ready (if using external database)
if [ "$DB_CONNECTION" != "sqlite" ]; then
    echo "Waiting for database connection..."
    until nc -z "$DB_HOST" "$DB_PORT"; do
        echo "Database is unavailable - sleeping"
        sleep 1
    done
    echo "Database is up - continuing"
fi

# Run Laravel commands
echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

# Create storage link if it doesn't exist
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Set correct permissions again
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache
echo "Starting Apache..."
apache2-foreground
