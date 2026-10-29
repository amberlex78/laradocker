#!/usr/bin/env sh

set -eu

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [ -f artisan ] && [ -f vendor/autoload.php ]; then
    php artisan package:discover --ansi >/dev/null
fi

exec "$@"
