FROM php:8.1.0-fpm-alpine
WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ icu-dev libpq-dev zsh git vim linux-headers inotify-tools\
    && pecl install apcu \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
        opcache \
        intl \
        pdo_pgsql \
        pgsql \
    && docker-php-ext-enable \
        apcu \
        pdo_pgsql \
        xdebug \
        opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY etc/infrastructure/php/ /usr/local/etc/php/
