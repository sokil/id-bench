FROM php:8.1.17-cli-alpine

WORKDIR /bench

COPY --from=composer:2.5.5 /usr/bin/composer /usr/bin/composer

RUN apk add \
    # Required for MongoDB
    zstd-dev icu-dev \
    # Required for PostgreSQL
    postgresql-dev
RUN apk add --virtual .build-deps $PHPIZE_DEPS
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql
RUN pecl install mongodb-1.15.1 && docker-php-ext-enable mongodb
RUN apk del .build-deps

