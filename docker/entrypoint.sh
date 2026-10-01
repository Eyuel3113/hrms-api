#!/bin/sh
set -e

# Dynamically set Render's PORT in Nginx config (defaults to 80)
PORT="${PORT:-80}"
sed -i "s/PORT_PLACEHOLDER/$PORT/g" /etc/nginx/conf.d/default.conf

# Ensure database directory and sqlite file exist and are writable
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Set permissions for Laravel storage, cache, and database directories
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Run database migrations and seeders
echo "Running database migrations..."
php artisan migrate --force || echo "Migration encountered an issue or skipped."

echo "Seeding database with demo data..."
php artisan db:seed --force || echo "Seeding encountered an issue or skipped."

# Cache configuration, routes, and views
echo "Caching Laravel configuration, routes, and views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
