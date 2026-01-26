#!/bin/bash

# Aller dans le dossier
cd /var/www/portfolio

# Récupérer les changements
echo "📥 Récupération du code..."
git pull origin main

# Installation safe
composer install --no-dev --optimize-autoloader

# Migrations et Cache
php artisan migrate --force
php artisan config:cache
php artisan view:cache

# Permissions (Important !)
chown -R www-data:www-data /var/www/portfolio

echo "✅ Portfolio mis à jour !"
