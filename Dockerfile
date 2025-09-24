FROM php:8.4-cli-alpine
COPY docker/php/app.ini ${PHP_INI_DIR}/conf.d/20-app.ini
WORKDIR /app
