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

# Update Nginx to use the port provided by Render
PORT="${PORT:-80}"
sed -i "s/listen 0.0.0.0:80;/listen 0.0.0.0:$PORT;/g" /etc/nginx/sites-enabled/default
sed -i "s/listen \[::\]:80;/listen \[::\]:$PORT;/g" /etc/nginx/sites-enabled/default

# Start Nginx
service nginx start

# Start PHP-FPM
php-fpm
