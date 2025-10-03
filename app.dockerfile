FROM php:8.3-fpm AS app

RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-requests \
    libpq-dev

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# config directory of work
WORKDIR /var/www

# copy code of laravel inside image
COPY . /var/www

# change UID
RUN usermod -u 1000 www-data

# install dependencies of laravel (production)
RUN composer install --no-dev --optimize-autoloader

USER www-data