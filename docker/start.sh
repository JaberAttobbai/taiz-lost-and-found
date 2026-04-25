#!/bin/sh

# Make sure SQLite db exists and has permissions
touch /var/www/database/database.sqlite
chown www-data:www-data /var/www/database/database.sqlite
chmod 664 /var/www/database/database.sqlite

# Run migrations and seed the database if it's empty
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Start Nginx
service nginx start

# Start PHP-FPM
php-fpm
