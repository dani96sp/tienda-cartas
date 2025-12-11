FROM php:8.2-apache

# Install system dependencies and PHP extensions for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application source
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

EXPOSE 8080
CMD ["apache2ctl", "-D", "FOREGROUND", "-k", "start", "-DFOREGROUND", "-E", "/dev/stderr"]
