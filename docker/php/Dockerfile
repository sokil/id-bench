FROM php:8.1.17-cli-alpine

WORKDIR /bench

RUN apk add --virtual .build-deps $PHPIZE_DEPS postgresql-dev
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql
RUN pecl install mongodb-1.15.1 && docker-php-ext-enable mongodb
RUN apk del .build-deps

