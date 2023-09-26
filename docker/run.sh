#!/bin/sh

cd /var/www

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

php artisan optimize

/usr/bin/supervisord -c /etc/supervisord.conf
