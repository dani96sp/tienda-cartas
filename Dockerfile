FROM php:8.2-fpm

RUN apt-get update && apt-get install -y curl

# Instalar Caddy binario estático
RUN curl -o /usr/bin/caddy -fsSL "https://caddyserver.com/api/download?os=linux&arch=amd64" \
    && chmod +x /usr/bin/caddy

RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html
WORKDIR /var/www/html

COPY Caddyfile /etc/caddy/Caddyfile

EXPOSE 80
CMD ["caddy", "run", "--config", "/etc/caddy/Caddyfile"]
