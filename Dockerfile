FROM php:8.1-apache

# Install required PHP extensions for CodeIgniter 4
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install mysqli pdo pdo_mysql intl

# Enable Apache rewrite rules
RUN a2enmod rewrite

# Copy your codebase into the server
COPY . /var/www/html/

# Point Apache to the CI4 public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Give CodeIgniter permission to write to the writable folder
RUN chown -R www-data:www-data /var/www/html/writable

EXPOSE 80