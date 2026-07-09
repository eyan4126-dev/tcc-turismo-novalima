#!/bin/sh
sed -i "s/listen 8080;/listen ${PORT:-8080};/g" /etc/nginx/sites-available/default
php-fpm -D
nginx -g "daemon off;"
