#!/bin/sh
set -e


# Copy fallback.html to public/index.html
cp /usr/local/share/fallback.html /var/www/html/index.html

# Create nginx config directory if it doesn't exist (Alpine uses http.d structure)
mkdir -p /etc/nginx/http.d

cp /usr/local/share/pre-nginx.conf /etc/nginx/http.d/default.conf

# Fix nginx user directive (replace nginx user with www-data)
sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf 2>/dev/null || true
# If user directive is commented or doesn't exist, add it
if ! grep -q "^user " /etc/nginx/nginx.conf; then
    sed -i '1i user www-data;' /etc/nginx/nginx.conf
fi

nginx -g "daemon off;" &
NGINX_PID=$!
echo "Maintaining page Nginx started with PID: $NGINX_PID"

# CRITICAL: Clear cached package/service discovery BEFORE any artisan command
# This prevents "Class not found" errors from dev-only packages like IDE Helper
rm -f /var/www/html/bootstrap/cache/*.php

# Clear existing caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Wait for a moment to ensure environment variables are loaded
sleep 1

# Install dev dependencies for non-production environments (e.g., debugbar)
cd /var/www/html/
if [ "$APP_ENV" != "production" ] && [ "$APP_ENV" != "prod" ]; then
    echo "Installing dev dependencies for $APP_ENV environment..."
    composer install --no-interaction --optimize-autoloader
else
    echo "Skipping dev dependencies for production environment"
fi

# Migrate the database
php artisan migrate --force

# Generate caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan domains:initialize-valid
php artisan domains:build-verified 
php artisan octane:install --server=swoole -n


# Create octane state directory for multiple instances
mkdir -p /var/www/html/storage/framework/octane
chown -R www-data:www-data /var/www/html/storage/framework/octane
chmod -R 755 /var/www/html/storage/framework/octane

# Clear any stale state file from previous runs to ensure clean startup
rm -f /var/www/html/storage/framework/octane/octane-server-state.json

rm -f /var/www/html/storage/logs/worker.backup.log
rm -f /var/www/html/storage/logs/laravel.backup.log


if [ -f /var/www/html/storage/logs/worker.log ]; then
    mv /var/www/html/storage/logs/worker.log /var/www/html/storage/logs/worker.backup.log
fi
if [ -f /var/www/html/storage/logs/laravel.log ]; then
    mv /var/www/html/storage/logs/laravel.log /var/www/html/storage/logs/laravel.backup.log
fi
touch /var/www/html/storage/logs/worker.log
chown www-data:www-data /var/www/html/storage/logs/worker.log
chmod 644 /var/www/html/storage/logs/worker.log

# Make all logs owner by www-data
chown -R www-data:www-data /var/www/html/storage/logs
# Permissions for all logs
chmod -R 755 /var/www/html/storage/logs

# Create Laravel log files if they don't exist and set permissions
touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/storage/logs/schedule.log
touch /var/www/html/storage/logs/video-worker.log
touch /var/www/html/storage/logs/ssr.log
touch /var/www/html/storage/logs/ssr-errors.log
touch /var/www/html/storage/logs/cron.log

# Create Octane log files (single instance)
touch /var/www/html/storage/logs/octane.log
touch /var/www/html/storage/logs/octane-errors.log
chown www-data:www-data /var/www/html/storage/logs/octane.log
chown www-data:www-data /var/www/html/storage/logs/octane-errors.log
chmod 644 /var/www/html/storage/logs/octane.log
chmod 644 /var/www/html/storage/logs/octane-errors.log

chown www-data:www-data /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/schedule.log
chown www-data:www-data /var/www/html/storage/logs/video-worker.log
chown www-data:www-data /var/www/html/storage/logs/ssr.log
chown www-data:www-data /var/www/html/storage/logs/ssr-errors.log
chown www-data:www-data /var/www/html/storage/logs/cron.log
chmod 644 /var/www/html/storage/logs/laravel.log
chmod 644 /var/www/html/storage/logs/worker.log
chmod 644 /var/www/html/storage/logs/schedule.log
chmod 644 /var/www/html/storage/logs/video-worker.log
chmod 644 /var/www/html/storage/logs/ssr.log
chmod 644 /var/www/html/storage/logs/ssr-errors.log
chmod 644 /var/www/html/storage/logs/cron.log

# Remove maintenance page
rm -f /var/www/html/index.html


echo "Killing maintaining page Nginx"
# Kill nginx
kill $NGINX_PID

echo "Maintaining page Nginx killed"

# Copy post-nginx.conf to /etc/nginx/http.d/default.conf
cp /usr/local/share/nginx.conf /etc/nginx/http.d/default.conf

# Prepare Nginx client body temp directory used for uploads (use /tmp)
mkdir -p /tmp/nginx/client_body
# Ensure www-data (nginx runs as www-data via supervisord) can write
chown -R www-data:www-data /tmp/nginx
chmod -R 777 /tmp/nginx

echo "Testing nginx config"
nginx -t
# Set nginx permissions as maintaining page nginx may have wrote them as root
chown -R www-data:www-data /var/log/nginx 
chmod -R 755 /var/log/nginx
rm -f /run/nginx/nginx.pid
# Ensure /run/nginx directory exists with proper permissions for www-data to write pid file
mkdir -p /run/nginx
chown -R www-data:www-data /run/nginx
chmod -R 755 /run/nginx

sleep 5


echo "Starting supervisor with prod-nginx, php-fpm and workers"
# Start supervisor (which manages nginx and php-fpm)
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
