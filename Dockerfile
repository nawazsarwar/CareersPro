# Multi-stage build for better optimization

# Stage 1: Composer dependencies and route export
# Use PHP 8.3 base image with composer to match our production PHP version
FROM php:8.3-cli-alpine AS composer-builder
# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
# Create minimal .env BEFORE composer install to prevent broadcast service errors
RUN echo "BROADCAST_DRIVER=log" > .env
RUN composer install --no-interaction --no-scripts --prefer-dist --no-dev --optimize-autoloader --ignore-platform-reqs

# Export Ziggy routes to JSON for SSR (eliminates sending routes in page props)
# Laravel bootstrap requires these directories and minimal environment
COPY bootstrap/ ./bootstrap/
COPY config/ ./config/
COPY routes/ ./routes/
COPY app/ ./app/
# COPY scripts/export-ziggy-routes.php ./scripts/
# Create required directories and minimal .env for Laravel bootstrap
RUN mkdir -p resources/js storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && rm -f bootstrap/cache/*.php \
    && echo "APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" > .env \
    && echo "APP_ENV=production" >> .env \
    && echo "APP_URL=http://localhost" >> .env \
    && echo "BROADCAST_DRIVER=log" >> .env \
    && echo "PUSHER_APP_KEY=dummy" >> .env \
    && echo "PUSHER_APP_SECRET=dummy" >> .env \
    && echo "PUSHER_APP_ID=dummy" >> .env \
    && echo "PUSHER_APP_CLUSTER=mt1" >> .env

# Export translations to JSON for SSR (eliminates sending translations in page props)
# This script does NOT require Laravel - reads PHP files directly

# Stage 2: Node builder
FROM node:20-alpine AS node-builder

ARG VITE_PUSHER_APP_KEY
ARG VITE_PUSHER_APP_CLUSTER
ARG VITE_PUSHER_HOST
ARG VITE_PUSHER_PORT
ARG VITE_PUSHER_SCHEME
ARG VITE_APP_URL
ARG APP_ENV

# Set working directory for Node.js build
WORKDIR /app


# Copy package files for better caching
COPY package*.json ./
ENV APP_ENV=$APP_ENV \
    COMPOSER_ALLOW_SUPERUSER=1 \
    VITE_PUSHER_APP_KEY=$VITE_PUSHER_APP_KEY \
    VITE_PUSHER_APP_CLUSTER=$VITE_PUSHER_APP_CLUSTER \
    VITE_PUSHER_HOST=$VITE_PUSHER_HOST \
    VITE_PUSHER_PORT=$VITE_PUSHER_PORT \
    VITE_PUSHER_SCHEME=$VITE_PUSHER_SCHEME \
    VITE_APP_URL=$VITE_APP_URL

# Echo all the environment variables
RUN echo "Pusher app key: ${VITE_PUSHER_APP_KEY}!"
RUN echo "Pusher app cluster: ${VITE_PUSHER_APP_CLUSTER}!"
RUN echo "Pusher host: ${VITE_PUSHER_HOST}!"
RUN echo "Pusher port: ${VITE_PUSHER_PORT}!"
RUN echo "Pusher scheme: ${VITE_PUSHER_SCHEME}!"
RUN echo "App URL: ${VITE_APP_URL}!"
RUN echo "App environment: ${APP_ENV}!"


# Install dependencies (including dev dependencies needed for build)
RUN npm ci --no-audit --no-fund --ignore-scripts

# Copy source files needed for build
COPY resources/ ./resources/
COPY vite.config.js ./
COPY public/ ./public/

# Copy ziggy package from composer stage (needed for SSR build)
# COPY --from=composer-builder /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy
# Copy exported routes JSON for SSR (bundled at build time, not sent in page props)
# COPY --from=composer-builder /app/resources/js/ziggy-routes.json ./resources/js/
# Copy exported translations JSON for SSR (bundled at build time, not sent in page props)
# COPY --from=composer-builder /app/resources/js/lang ./resources/js/lang

# Build assets (skip PHP export scripts since we already ran them in composer-builder stage)
RUN npm run build && npm run build -- --ssr

# Main PHP image - using official Swoole image with PHP 8.3
FROM phpswoole/swoole:php8.3-alpine

ARG APP_ENV
ARG PUSHER_APP_KEY
ARG PUSHER_APP_SECRET
ARG PUSHER_APP_ID
ARG PUSHER_APP_CLUSTER
ARG VITE_PUSHER_APP_KEY
ARG VITE_PUSHER_APP_CLUSTER
ARG VITE_PUSHER_HOST
ARG VITE_PUSHER_PORT
ARG VITE_PUSHER_SCHEME

# Install PHP extensions and Node.js (needed for SSR)
RUN apk add --no-cache \
    nginx \
    supervisor \
    py3-pip \
    zip \
    unzip \
    git \
    curl \
    mariadb-client \
    imagemagick \
    imagemagick-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    gmp-dev \
    shadow \
    nodejs \
    npm \
    $PHPIZE_DEPS

# Install superlance for memory monitoring (memmon)
RUN pip3 install --break-system-packages superlance

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        gd \
        bcmath \
        pcntl \
        opcache \
        zip \
        intl \
        exif \
        gmp

# Install Redis extension (if not already installed)
RUN if ! php -m | grep -qi redis; then pecl install redis && docker-php-ext-enable redis; fi

# Install Imagick extension (if not already installed)
RUN if ! php -m | grep -qi imagick; then pecl install imagick && docker-php-ext-enable imagick; fi

# Verify Swoole is loaded
RUN php -v
RUN php -m | grep -i swoole && echo "Swoole extension loaded successfully!"

# Install Composer
COPY --from=composer/composer:latest /usr/bin/composer /usr/bin/composer

# Set environment variables
ENV APP_ENV=$APP_ENV \
    COMPOSER_ALLOW_SUPERUSER=1 \
    BROADCAST_DRIVER=log \
    PUSHER_APP_KEY=$PUSHER_APP_KEY \
    PUSHER_APP_SECRET=$PUSHER_APP_SECRET \
    PUSHER_APP_ID=$PUSHER_APP_ID \
    PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-interaction --no-scripts --prefer-dist --no-dev --optimize-autoloader

# Copy application files
COPY . .

# Clear ALL cached files that may reference dev dependencies
# This is critical to prevent "Class not found" errors at runtime
# The package discovery will happen during entrypoint
RUN rm -f bootstrap/cache/*.php

# Copy built assets from node-builder stage
COPY --from=node-builder /app/public/build ./public/build
# Copy SSR bundle for server-side rendering
COPY --from=node-builder /app/bootstrap/ssr ./bootstrap/ssr

# Create package.json for SSR to enable ESM imports in Node.js
RUN echo '{"type": "module"}' > /var/www/html/bootstrap/ssr/package.json

# Create directories and set permissions in a single layer
RUN mkdir -p \
    /var/log/supervisor \
    /var/log/nginx \
    /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/public/cache/images \
    /var/lib/nginx \
    /run/nginx \
    /var/lib/php/sessions \
    /var/lib/php/wsdlcache \
    /tmp/nginx/client_body \
    && chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/log/nginx \
        /var/log/supervisor \
        /var/lib/nginx \
        /run/nginx \
        /var/lib/php \
        /tmp/nginx \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/log/nginx \
    && chmod -R 777 /tmp/nginx

# Copy configuration files and entrypoint script
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/crontab /etc/crontabs/root
COPY docker/php.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY docker/start-ssr.sh /usr/local/bin/start-ssr.sh
COPY docker/fallback.html /usr/local/share/fallback.html
COPY docker/pre-nginx.conf /usr/local/share/pre-nginx.conf
COPY docker/nginx.conf /usr/local/share/nginx.conf

# Fix nginx user directive
RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf 2>/dev/null || true \
    && sed -i 's/user nginx;/user www-data;/g' /etc/nginx/http.d/*.conf 2>/dev/null || true \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/start-ssr.sh \
    && mkdir -p /var/log/cron \
    && chmod 600 /etc/crontabs/root

# Clean up unnecessary files to reduce image size
RUN rm -rf \
    /var/www/html/node_modules \
    /var/www/html/resources/assets \
    /var/www/html/tests \
    /var/www/html/.git \
    /var/www/html/.github \
    /var/www/html/docs \
    /var/www/html/memory-bank \
    /var/www/html/maizzle \
    /var/www/html/scripts \
    /var/www/html/docker \
    /tmp/* \
    /var/cache/apk/*

# Expose port 80
EXPOSE 80

# Health check configuration
# - interval: check every 15 seconds
# - timeout: fail if no response in 5 seconds
# - start-period: wait 60 seconds before first check (allow app to boot)
# - retries: mark unhealthy after 3 consecutive failures
# Uses the lightweight /api/health/live endpoint (just checks Octane is responding)
HEALTHCHECK --interval=15s --timeout=5s --start-period=60s --retries=3 \
    CMD curl -f http://127.0.0.1:80/api/health/live || exit 1

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
