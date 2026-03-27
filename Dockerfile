FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js pour compiler les assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

CMD php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
