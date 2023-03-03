FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        libonig-dev \
        libzip-dev \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) mbstring iconv mysqli pdo_mysql zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

# Install chrome wget
# Install Chrome dependencies
RUN apt-get install -y fonts-liberation libasound2 libnspr4 libnss3 xdg-utils libatk-bridge2.0-0 libatk1.0-0 libatspi2.0-0 libcairo2 libcups2 libgbm1 libgdk-pixbuf2.0-0 libgtk-3-0 libpango-1.0-0 libpangocairo-1.0-0 libxkbcommon0 libu2f-udev
# Download Chrome
RUN wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
# Install Chrome (TODO: Replace dpkg with gdebi)
RUN dpkg -i google-chrome-stable_current_amd64.deb
# Remove Chrome package
RUN rm google-chrome-stable_current_amd64.deb

WORKDIR /var/www
CMD ["php-fpm"]
