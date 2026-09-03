#!/bin/bash
set -e

echo "🚀 Starting Laravel deployment..."

# Ensure storage and cache directories exist and are writable
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

chmod -R 775 storage bootstrap/cache

echo "✅ Directories configured"

# Run migrations if APP_KEY is set (meaning app is configured)
if [ -n "$APP_KEY" ]; then
    echo "🔄 Running database migrations..."
    php artisan migrate --force --no-interaction
    echo "✅ Migrations completed"
else
    echo "⚠️  APP_KEY not set - skipping migrations"
fi

php artisan schedule:work &

# Start queue worker in background
echo "🔄 Starting queue worker..."
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 &

# Start the application
echo "🎉 Starting Laravel application..."
exec php artisan serve --host=0.0.0.0 --port=8000
