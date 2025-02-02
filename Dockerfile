FROM php:8.2-apache

# Install PHP extensions and required packages
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Apache configuration for directory handling
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Add Alias for assets directory
RUN echo "Alias /assets /var/www/assets" > /etc/apache2/conf-available/assets.conf \
    && a2enconf assets

# Configure Apache user and permissions
RUN chown -R www-data:www-data /var/www/ \
    && chmod -R 755 /var/www/

# Set Apache user and group
RUN sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=root/' /etc/apache2/envvars \
    && sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=root/' /etc/apache2/envvars
