FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    nodejs \
    npm \
    libzip-dev \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN mkdir -p database \
    && touch database/database.sqlite

RUN chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]
