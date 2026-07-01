#!/bin/sh

# Exit immediately if a command exits with a non-zero status
set -e

# Generate APP_KEY if it's empty in .env and not provided via env var
if ! grep -q "^APP_KEY=base64:" .env && [ -z "$APP_KEY" ]; then
    echo "Generating new Laravel APP_KEY..."
    php artisan key:generate --force
fi

# Link storage folder if not already linked
if [ ! -d "public/storage" ]; then
    echo "Creating public/storage symlink..."
    php artisan storage:link || true
fi

# Cache config, routes, and views for production optimization
echo "Caching Laravel configuration, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Wait for MySQL to be ready
echo "Waiting for database connection (db:3306)..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database is ready!"

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Start Supervisor (which starts Nginx and PHP-FPM)
echo "Starting Supervisor..."
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
