# PHP 8.4 Alpine for development and tests (Symfony 8.x lock requires >=8.4)
FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    git \
    unzip \
    bash \
    libzip-dev

RUN docker-php-ext-install -j$(nproc) zip

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN git config --global --add safe.directory /app

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="/app/vendor/bin:${PATH}"
