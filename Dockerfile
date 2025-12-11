FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Caddy estático
RUN curl -o /usr/bin/caddy -fsSL "https://caddyserver.com/api/download?os=linux&arch=amd64" \
    && chmod +x /usr/bin/caddy

# Copiar proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Copiar Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Copiar script de arranque
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["start.sh"]
