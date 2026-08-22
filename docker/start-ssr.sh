#!/bin/sh
# SSR startup script with explicit memory limits
# This ensures NODE_OPTIONS is set before Node.js starts

# Set Node.js memory limit (512MB heap size)
# This prevents unbounded memory growth in the SSR server
export NODE_OPTIONS="--max-old-space-size=512"

# Log startup info for debugging
echo "[SSR] Starting Inertia SSR server..."
echo "[SSR] NODE_OPTIONS: $NODE_OPTIONS"
echo "[SSR] Date: $(date)"

# Start the SSR server via artisan
exec php /var/www/html/artisan inertia:start-ssr
