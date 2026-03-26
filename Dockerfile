FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache DocumentRoot pour Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

WORKDIR /var/www/html

# Copier composer.json/lock en premier pour profiter du cache Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# Copier le reste du code
COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY .env.example .env
RUN php artisan key:generate --force

EXPOSE 80

CMD ["apache2-foreground"]
