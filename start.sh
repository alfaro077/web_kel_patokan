#!/bin/bash
echo "Creating storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/app/public
mkdir -p storage/logs

echo "Running Laravel optimizations..."
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
# We explicitly removed php artisan view:cache to fix the Volume realpath() crash on Railway

echo "Running migrations..."
php artisan migrate --force

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
