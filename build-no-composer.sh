#!/usr/bin/env bash

echo "=== HOMYGO NO-COMPOSER BUILD ==="
echo "Build started at: $(date)"

# Set error handling
set -e

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Creating minimal vendor autoload..."
    mkdir -p vendor
    echo "<?php
    // Minimal autoloader
    spl_autoload_register(function(\$class) {
        \$file = __DIR__ . '/../app/' . str_replace('\\\\', '/', \$class) . '.php';
        if (file_exists(\$file)) {
            require \$file;
        }
    });
    " > vendor/autoload.php
fi

# Use emergency routes
echo "Switching to emergency routes..."
cp routes/emergency.php routes/web.php

# Create basic .env if not exists
if [ ! -f ".env" ]; then
    echo "Creating basic .env..."
    cat > .env << EOF
APP_NAME=HomyGo
APP_ENV=production
APP_DEBUG=true
APP_KEY=base64:$(openssl rand -base64 32)
APP_URL=https://homygo.info
LOG_CHANNEL=stderr
LOG_LEVEL=debug
DB_CONNECTION=pgsql
CACHE_DRIVER=array
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=sync
EOF
fi

echo "No-composer build completed!"
echo "Starting PHP server on port $PORT"
