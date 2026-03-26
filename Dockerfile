FROM php:8.4-apache

# 1. Désactiver les MPM conflictuels et forcer prefork
RUN a2dismod mpm_event && a2enmod mpm_prefork

RUN apt-get update && apt-get install -y \
    libzip-dev libonig-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Configurer Apache pour écouter sur le port variable de Railway ($PORT)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configure Apache DocumentRoot pour Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

COPY . .

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# On ne génère pas de clé ici si tu l'as déjà mise dans les variables Railway
# Mais on garde la structure pour la sécurité
RUN php artisan key:generate --force

# Railway ignore EXPOSE, il utilise sa propre configuration de port
EXPOSE 80

# Lancer les migrations avant de démarrer Apache
CMD sh -c "php artisan migrate --force && apache2-foreground"
