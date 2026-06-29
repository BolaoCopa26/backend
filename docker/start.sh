#!/bin/sh

# Use the PORT environment variable provided by Render, default to 8080
PORT="${PORT:-8080}"

# Replace the port in Nginx configuration
sed -i "s/listen 8080;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
