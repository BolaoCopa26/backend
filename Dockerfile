FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    nginx

# Install Node.js 20.x (needed for Vite build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring bcmath exif pcntl gd zip intl opcache

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install frontend dependencies and build
# Note: Using npm install instead of npm ci because package-lock.json was not found
RUN npm install && npm run build

# Install backend dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup Permissions and ensure directories exist
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Run Laravel Optimization Commands
RUN php artisan config:cache \
    && php artisan view:cache
# Note: route:cache not executed due to presence of Closures in routes/web.php

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Make start.sh executable
RUN chmod +x docker/start.sh

# Expose port (Render sets PORT env variable, our start.sh will adapt to it)
EXPOSE 8080

# Run the start script
CMD ["/var/www/html/docker/start.sh"]
