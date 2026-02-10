FROM php:8.2-cli

RUN apt-get update && apt-get install -y libpq-dev libzip-dev zip && docker-php-ext-install pdo pdo_pgsql zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /auditable