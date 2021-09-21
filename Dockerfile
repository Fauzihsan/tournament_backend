FROM php:7.4.22-fpm
LABEL maintainer="surahman325@gmail.com"

COPY . /tmp/app

RUN apt-get update \
    && apt-get install -y \
    libzip-dev libpng-dev libpq-dev gnupg2 nano\
    wget \
    && docker-php-ext-install pdo pgsql pdo_pgsql zip\
    && docker-php-ext-enable zip gd\
    && cd /tmp/app \
    && rm -f .env \
    && rm -rf vendor \
    && rm -rf etc \
    && rm -rf .git \
    && rm -rf storage/session/* \
    && rm -rf storage/views/* \
    && rm -rf storage/cache/* \
    cp -rf /tmp/app/* /var/www/html/; \
    chown -Rf www-data.www-data /var/www/html; \
    rm -rf /tmp/app;

WORKDIR /var/www/html
ENTRYPOINT "php-fpm -F -O"
