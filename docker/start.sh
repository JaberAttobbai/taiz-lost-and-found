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

# Start Nginx
service nginx start

# Start PHP-FPM
php-fpm
