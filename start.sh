#!/bin/bash
echo "Creating storage directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/app/public
mkdir -p storage/logs

echo "Running Laravel optimizations..."
rm -rf public/storage
php artisan storage:link || true
php artisan config:cache
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
