#!/bin/bash

# Start script for Render deployment
set -e

echo "Starting HomyGO application..."

# Ensure database exists
if [ ! -f "database/database.sqlite" ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Run any pending migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Seed roles if they don't exist
echo "Ensuring roles exist..."
php artisan db:seed --class=RoleSeeder --force --no-interaction

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Start the application on the port specified by Render
echo "Starting PHP server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT
