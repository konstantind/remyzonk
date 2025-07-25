FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    libssl-dev\
    libpq-dev \
    && pecl install redis \
    && docker-php-ext-install -j$(nproc) iconv mbstring pdo zip pdo_pgsql pgsql \
    && docker-php-ext-enable redis pdo_pgsql pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/php-custom.ini

COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
RUN sed -i 's|^include=.*|include=/usr/local/etc/php-fpm.d/*.conf|' /usr/local/etc/php-fpm.conf

WORKDIR /data
VOLUME /data

CMD ["php-fpm"]
