#!/bin/bash
set -e

if [[ -n ${ENV_FILE:-} ]]; then
  {
    echo ;
    echo "$ENV_FILE"
  } >> /var/www/tflashgame/release/${APP_ENV}.env
  php artisan optimize
fi

docker-php-entrypoint $@
