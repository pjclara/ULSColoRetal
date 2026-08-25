#!/usr/bin/env bash

set -e

echo "==> Maintenance checks"

php artisan down --render="errors::503" --retry=60 || true

echo "==> Installing dependencies"

composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

echo "==> Building frontend"

npm ci
npm run build

echo "==> Database migrations"

php artisan migrate --force

echo "==> Optimizing Laravel"

php artisan optimize

echo "==> Storage"

php artisan storage:link || true

echo "==> Restarting queues"

php artisan queue:restart

echo "==> Application online"

php artisan up

echo "Deployment completed."