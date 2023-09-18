# Use the official PHP image
FROM php:8.1.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip
RUN docker-php-ext-install mysqli
# Install the bcmath extension
RUN docker-php-ext-install bcmath
# Copy custom php.ini to the PHP configuration directory
COPY conf/php.ini /usr/local/etc/php/
COPY . .
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -
RUN apt-get install -y nodejs
# RUN apt-get install -y npm
# RUN composer install

# Khởi động PHP-FPM
CMD ["php-fpm"]
EXPOSE 9000
# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache