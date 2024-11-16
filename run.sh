#!/usr/bin/env sh

set -e

env=${APP_ENV:-production}

cd /var/www

if [ "$env" == "production" ]; then
    composer install --no-dev
else
    composer install
fi

php artisan migrate --seed --force

if [ ${env} = "production" ]; then
    php artisan optimize
else
    php artisan optimize:clear
fi

php artisan octane:start --host=0.0.0.0
