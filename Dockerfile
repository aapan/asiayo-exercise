FROM php:8.3-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y

# Set the working directory
COPY . /var/www/app
WORKDIR /var/www/app

# install composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# install dependencies
RUN composer install

# Set the default command to run php-fpm
CMD ["php-fpm"]

# Expose port 9000 and start php-fpm server
EXPOSE 9000
