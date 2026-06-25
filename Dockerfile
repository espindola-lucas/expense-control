# ──────────────────────────────────────────────────────────────────
# Base: PHP extensions + Nginx + Composer – shared by all stages
# ──────────────────────────────────────────────────────────────────
FROM php:8.3-fpm AS base

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        libpq-dev \
        libzip-dev \
        nginx \
        supervisor \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pgsql zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN rm -f /etc/nginx/sites-enabled/default

COPY docker/nginx/api.conf   /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

# ──────────────────────────────────────────────────────────────────
# Dev: full Composer deps, Xdebug enabled, source mounted at runtime
# ──────────────────────────────────────────────────────────────────
FROM base AS dev

COPY docker/php/dev.ini $PHP_INI_DIR/conf.d/app.ini

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload \
    && chown -R www-data:www-data storage bootstrap/cache

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

# ──────────────────────────────────────────────────────────────────
# Prod: no dev deps, Laravel caches built on first startup
# ──────────────────────────────────────────────────────────────────
FROM base AS prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/prod.ini $PHP_INI_DIR/conf.d/app.ini

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.prod.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
