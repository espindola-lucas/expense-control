FROM php:8.3-fpm
RUN apt-get update
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# cambiamos el UID del usuario www-data
RUN usermod -u 1000 www-data