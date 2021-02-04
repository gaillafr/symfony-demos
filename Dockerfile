# Production image
FROM php:8-fpm-buster as web

MAINTAINER François Gaillard <gailla.fr@gmail.com>

ENV BASE_DIR        /var/www/html
ENV DEBIAN_FRONTEND noninteractive
ENV DEBIAN_CODENAME buster
ENV TZ              UTC

ARG APP_ENV=prod
ARG STABILITY="stable"
ARG SYMFONY_VERSION=""

ENV STABILITY ${STABILITY:-stable}

# System
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata

# PHP
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends \
        git \
        gnupg \
        icu-devtools \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libmcrypt4 \
        libmcrypt-dev \
        libpng-dev \
        libwebp-dev \
        libxpm-dev \
        libzip-dev \
        unzip \
        zlib1g-dev \
    && apt-get clean all \
    && pecl install \
        apcu \
        mcrypt \
    && docker-php-ext-configure gd \
        --enable-gd \
        --with-webp \
        --with-jpeg \
        --with-xpm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) \
        exif \
        gd \
        intl \
        pdo_mysql \
        zip \
    && docker-php-ext-enable \
        apcu \
        mcrypt \
        opcache \
    && apt-get purge -y \
        libicu-dev \
        libmcrypt-dev \
    && apt-get autoremove -y \
    && rm -rf /tmp/*

# PHP configuration
COPY _docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY _docker/php/conf.d/prod.ini $PHP_INI_DIR/conf.d/custom.ini

RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Nginx
RUN apt-key adv --fetch-keys http://nginx.org/keys/nginx_signing.key \
    && echo "deb http://nginx.org/packages/mainline/debian/ ${DEBIAN_CODENAME} nginx" > /etc/apt/sources.list.d/nginx.list \
    && apt-get update \
    && apt-get install -y \
        nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && ln -sf /proc/1/fd/1 /var/log/nginx/access.log \
    && ln -sf /proc/1/fd/2 /var/log/nginx/error.log

COPY _docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Supervisord
RUN apt-get update \
    && apt-get install -y \
        supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY _docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR ${BASE_DIR}

# Copy source files and install dependencies
COPY . .

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    composer run-script --no-dev post-install-cmd; \
    chmod +x bin/console; sync

RUN chown -R www-data:www-data /var/www

# Run
COPY _docker/entrypoint.sh /entrypoint.sh
RUN  chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh", "/usr/bin/supervisord"]

CMD        ["-c", "/etc/supervisor/supervisord.conf"]

# Development image
FROM web as web-dev

ENV APP_ENV dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

# PHP configuration
COPY _docker/php/conf.d/dev.ini $PHP_INI_DIR/conf.d/custom.ini

RUN rm $PHP_INI_DIR/php.ini \
    && ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

# Install dependencies
RUN set -eux; \
    composer install --prefer-dist --no-progress --no-scripts --no-interaction; \
    composer dump-autoload --classmap-authoritative; \
    composer run-script post-install-cmd; \
    chmod +x bin/console; sync

RUN chown -R www-data:www-data /var/www

