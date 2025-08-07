FROM php:8.3-fpm-alpine

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk add --no-cache git curl bash icu-dev libpq-dev \
 && docker-php-ext-configure intl \
 && docker-php-ext-install -j$(nproc) intl pdo pdo_pgsql opcache

# Opcache
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=0'; \
  echo 'opcache.jit_buffer_size=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data composer.json composer.lock symfony.lock* ./
ARG APP_ENV=prod
ENV APP_ENV=${APP_ENV} COMPOSER_ALLOW_SUPERUSER=1

RUN if [ "$APP_ENV" = "prod" ]; then \
      composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts; \
    else \
      composer install --no-interaction --no-scripts; \
    fi

COPY --chown=www-data:www-data . .

RUN mkdir -p var && chown -R www-data:www-data var

EXPOSE 9000
CMD ["php-fpm"]
