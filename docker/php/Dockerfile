FROM php:8.1-fpm
RUN docker-php-ext-install pdo pdo_mysql
# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && \
    apt-get install -y \
    libxml2-dev \
    git

RUN docker-php-ext-install soap