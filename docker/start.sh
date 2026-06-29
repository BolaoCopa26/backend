#!/bin/sh

# Use the PORT environment variable provided by Render, default to 8080
PORT="${PORT:-8080}"

# Replace the port in Nginx configuration
sed -i "s/listen 8080;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf

# Run Laravel Optimization Commands at runtime to pick up Render Environment Variables
php artisan config:cache
php artisan view:cache

# Run migrations and seeders with a 60s timeout
# This prevents Render from hanging for 15 minutes if the DB is unreachable
timeout 60s php artisan migrate --force || echo "Migration failed or timed out"
timeout 60s php artisan db:seed --force || echo "Seeder failed or timed out"

# Start PHP-FPM in background
php-fpm &

# Start Nginx in foreground
nginx -g "daemon off;"
