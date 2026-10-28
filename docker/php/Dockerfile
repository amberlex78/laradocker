ARG PHP_IMAGE=php:8.5-fpm-bookworm

# ──────────────────────────────────────────────────────────────────────────────
# PHP EXTENSIONS
# ──────────────────────────────────────────────────────────────────────────────
# Build native PHP extensions once and reuse them in the runtime stages.
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

# ──────────────────────────────────────────────────────────────────────────────
# COMPOSER
# ──────────────────────────────────────────────────────────────────────────────
# Keep Composer isolated from the PHP runtime image.
FROM composer:2.9.8 AS composer-bin

# ──────────────────────────────────────────────────────────────────────────────
# FRONTEND BUILD
# ──────────────────────────────────────────────────────────────────────────────
# Laravel renders the Vite tags in the PHP container, so the production PHP
# image must contain the manifest as well as the Nginx image serving the files.
FROM node:24-bookworm-slim AS frontend-builder

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

# ──────────────────────────────────────────────────────────────────────────────
# SHARED PHP-FPM RUNTIME
# ──────────────────────────────────────────────────────────────────────────────
# Shared PHP-FPM runtime: system libraries, non-root user and Laravel runtime files.
FROM ${PHP_IMAGE} AS app-base

ARG APP_UID=1000
ARG APP_GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu72 \
        libonig5 \
        libzip4 \
        unzip \
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

# Copy compiled extensions and Composer into the shared runtime stage.
COPY --from=extension-builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=extension-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=composer-bin /usr/bin/composer /usr/local/bin/composer

RUN rm -f /usr/local/etc/php-fpm.d/www.conf

COPY docker/php/conf.d/ /usr/local/etc/php/conf.d/
COPY docker/php/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=app:app docker/php/entrypoint.sh /usr/local/bin/laravel-entrypoint

RUN chmod 755 /usr/local/bin/laravel-entrypoint

ENV HOME=/home/app

WORKDIR /var/www/html
USER app

# ──────────────────────────────────────────────────────────────────────────────
# DEVELOPMENT DEPENDENCIES
# ──────────────────────────────────────────────────────────────────────────────
# Install all Composer dependencies for local development, including require-dev.
FROM app-base AS vendor-dev

COPY --chown=app:app composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts

# ──────────────────────────────────────────────────────────────────────────────
# PRODUCTION DEPENDENCIES
# ──────────────────────────────────────────────────────────────────────────────
# Install only production Composer dependencies.
FROM app-base AS vendor-prod

COPY --chown=app:app composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

# ──────────────────────────────────────────────────────────────────────────────
# DEVELOPMENT IMAGE
# ──────────────────────────────────────────────────────────────────────────────
# Source code is bind-mounted by docker-compose.dev.yml.
FROM app-base AS dev

ENV APP_ENV=local \
    APP_DEBUG=true

COPY --from=vendor-dev --chown=app:app /var/www/html/vendor /var/www/html/vendor
COPY --chown=app:app . .

RUN composer dump-autoload --no-interaction --no-scripts \
    && php artisan package:discover --ansi

ENTRYPOINT ["/usr/local/bin/laravel-entrypoint"]
CMD ["php-fpm", "-F"]

# ──────────────────────────────────────────────────────────────────────────────
# PRODUCTION IMAGE
# ──────────────────────────────────────────────────────────────────────────────
# Self-contained source, vendor and optimized autoloader.
FROM app-base AS prod

ENV APP_ENV=production \
    APP_DEBUG=false

COPY --from=vendor-prod --chown=app:app /var/www/html/vendor /var/www/html/vendor
COPY --chown=app:app . .
COPY --from=frontend-builder --chown=app:app /var/www/html/public/build /var/www/html/public/build

RUN composer dump-autoload --no-dev --no-interaction --no-scripts --classmap-authoritative \
    && php artisan package:discover --ansi

ENTRYPOINT ["/usr/local/bin/laravel-entrypoint"]
CMD ["php-fpm", "-F"]
