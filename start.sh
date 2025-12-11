#!/bin/sh
set -e

# Start PHP-FPM
php-fpm -D

# Start Caddy
exec caddy run --config /etc/caddy/Caddyfile
