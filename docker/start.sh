#!/bin/sh

# Make sure SQLite db exists and has permissions
mkdir -p /var/www/database
touch /var/www/database/database.sqlite
chown -R www-data:www-data /var/www/database
chmod -R 775 /var/www/database

# Run migrations and seed the database if it's empty
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Remove static sitemap.xml if it exists (Laravel route handles this dynamically)
rm -f /var/www/public/sitemap.xml

# Clear application cache (ensures sitemap cache is refreshed on deploy)
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Update Nginx to use the port provided by Render
PORT="${PORT:-80}"
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/sites-enabled/default

# Start Nginx
service nginx start

# Start Queue Worker in background (for sending emails etc.)
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 &

# Start PHP-FPM
php-fpm
