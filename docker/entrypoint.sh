#!/bin/bash
set -e

cd /var/www/html

echo "--- Diagnostic: Current user and permissions ---"
id
ls -la storage/logs || echo "storage/logs not found"

echo "--- Fixing storage & cache permissions ---"
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
# Remove legacy log file if it's blocking us
rm -f storage/logs/laravel.log
touch storage/logs/laravel.log

chmod -R 777 storage bootstrap/cache
echo "--- Diagnostic: After fix ---"
ls -la storage/logs

echo "--- Installing Composer dependencies ---"
php composer.phar install --no-interaction --prefer-dist --optimize-autoloader

echo "--- Building frontend assets ---"
npm ci && npm run build

echo "--- Waiting for MySQL to be ready ---"
until php artisan db:show --no-interaction > /dev/null 2>&1; do
    echo "  MySQL not ready yet, sleeping 2s..."
    sleep 2
done

echo "--- Running migrations ---"
php artisan migrate --force

echo "--- Seeding database ---"
php artisan db:seed --force --no-interaction 2>/dev/null || true

echo "--- Caching config, routes, views ---"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Starting Apache ---"
exec apache2-foreground
