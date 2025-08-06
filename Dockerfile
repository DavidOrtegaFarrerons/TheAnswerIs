FROM php:8.3-fpm-alpine

# System deps
RUN apk add --no-cache git curl bash icu-dev oniguruma-dev libpq-dev

# PHP extensions
RUN docker-php-ext-configure intl \
 && docker-php-ext-install -j$(nproc) intl pdo pdo_pgsql opcache

# Opcache for prod
RUN { \
  echo 'opcache.enable=1'; \
  echo 'opcache.enable_cli=0'; \
  echo 'opcache.validate_timestamps=0'; \
  echo 'opcache.jit_buffer_size=0'; \
} > /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy app
COPY . /var/www/html

# Install PHP deps (prod defaults)
USER www-data

ARG APP_ENV=prod
RUN if [ "$APP_ENV" = "prod" ]; then \
      composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader; \
    else \
      composer install --no-interaction; \
    fi

# Writable cache/logs
RUN chown -R www-data:www-data var \
 && find var -type d -print0 | xargs -0 chmod 775 || true

USER www-data

CMD ["php-fpm"]
