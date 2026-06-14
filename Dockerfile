FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    nodejs \
    npm \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chmod -R 775 storage bootstrap/cache

RUN touch database/database.sqlite

RUN php artisan config:clear

RUN a2enmod rewrite

EXPOSE 80

CMD php artisan migrate --force && apache2-foreground