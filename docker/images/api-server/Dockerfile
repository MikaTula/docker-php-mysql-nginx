FROM php:8.5.13-fpm

COPY --from=composer:2.9.3 /usr/bin/composer /usr/bin/composer

# openssl, curl, mbstring and fileinfo are already enabled in the official image.
RUN apt-get update && apt-get install -y --no-install-recommends \
        mc \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd mysqli pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug redis && docker-php-ext-enable xdebug redis

ADD docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Official image enables OPcache by default.
RUN rm -f "$PHP_INI_DIR/conf.d/docker-php-ext-opcache.ini"

ADD custom.ini "$PHP_INI_DIR/conf.d/custom.ini"

