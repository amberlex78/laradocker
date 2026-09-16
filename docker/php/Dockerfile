ARG PHP_IMAGE=php:8.5-fpm-bookworm

FROM ${PHP_IMAGE} AS extension-builder

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        libonig-dev \
        libzip-dev \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        mbstring \
        pdo_mysql \
        zip \
    && rm -rf /var/lib/apt/lists/*

FROM composer:2.9.8 AS composer-bin

FROM ${PHP_IMAGE} AS app-base

ARG APP_UID=1000
ARG APP_GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu72 \
        libonig5 \
        libzip4 \
    && rm -rf /var/lib/apt/lists/* \
    && groupadd --gid "${APP_GID}" app \
    && useradd --uid "${APP_UID}" --gid "${APP_GID}" --create-home --shell /usr/sbin/nologin app \
    && mkdir -p /var/www/html \
        /var/www/html/bootstrap/cache \
        /var/www/html/storage/framework/cache/data \
        /var/www/html/storage/framework/sessions \
        /var/www/html/storage/framework/views \
        /var/www/html/storage/logs \
    && chown -R app:app /var/www/html

COPY --from=extension-builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=extension-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer

RUN rm -f /usr/local/etc/php-fpm.d/www.conf

COPY docker/php/conf.d/ /usr/local/etc/php/conf.d/
COPY docker/php/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=app:app docker/php/entrypoint.sh /usr/local/bin/laravel-entrypoint

RUN chmod 755 /usr/local/bin/laravel-entrypoint

WORKDIR /var/www/html
USER app

FROM app-base AS vendor-dev

COPY --chown=app:app composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts

FROM app-base AS vendor-prod

COPY --chown=app:app composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

FROM app-base AS dev

ENV APP_ENV=local \
    APP_DEBUG=true

COPY --from=vendor-dev --chown=app:app /var/www/html/vendor /var/www/html/vendor
COPY --chown=app:app . .

RUN composer dump-autoload --no-interaction --no-scripts \
    && php artisan package:discover --ansi

ENTRYPOINT ["/usr/local/bin/laravel-entrypoint"]
CMD ["php-fpm", "-F"]

FROM app-base AS prod

ENV APP_ENV=production \
    APP_DEBUG=false

COPY --from=vendor-prod --chown=app:app /var/www/html/vendor /var/www/html/vendor
COPY --chown=app:app . .

RUN composer dump-autoload --no-dev --no-interaction --no-scripts --classmap-authoritative \
    && php artisan package:discover --ansi

ENTRYPOINT ["/usr/local/bin/laravel-entrypoint"]
CMD ["php-fpm", "-F"]
