#!/bin/bash

# Script de déploiement en production
# Usage: ./deploy.sh [branch]
# Ou depuis n'importe quel dossier: ./deploy.sh [project_name] [branch]
# Examples:
#   ./deploy.sh                      # Détecte automatiquement le projet courant, branche main
#   ./deploy.sh develop              # Branche develop, projet détecté automatiquement
#   ./deploy.sh portfolio main       # Spécifier explicitement (si utile)

set -e  # Arrêter si une erreur occur

# Couleurs pour l'output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Variables
BASE_PATH="/var/www"

# Auto-détection du projet si on est dans un repo
CURRENT_DIR=$(basename "$(pwd)")
if [ "$CURRENT_DIR" = "portfolio" ] || [ "$CURRENT_DIR" = "mio-ressources-site" ]; then
    PROJECT_NAME="$CURRENT_DIR"
    BRANCH="${1:-main}"
else
    # Mode manuel si on n'est pas dans un dossier projet
    PROJECT_NAME="${1:-portfolio}"
    BRANCH="${2:-main}"
fi

PROJECT_PATH="$BASE_PATH/$PROJECT_NAME"

# Vérifier les paramètres
if [ ! -d "$PROJECT_PATH" ]; then
    echo -e "${RED}❌ Erreur: Le projet '$PROJECT_NAME' n'existe pas à $PROJECT_PATH${NC}"
    exit 1
fi

echo -e "${YELLOW}🚀 Déploiement de $PROJECT_NAME (branche: $BRANCH)${NC}"

# 1. Aller dans le répertoire
cd "$PROJECT_PATH"
echo -e "${YELLOW}📁 Répertoire: $(pwd)${NC}"

# 2. Récupérer les dernières modifs
echo -e "${YELLOW}📥 Git pull...${NC}"
git pull origin "$BRANCH" || { echo -e "${RED}❌ Git pull échoué${NC}"; exit 1; }

# 3. Installer les dépendances PHP
echo -e "${YELLOW}📦 Composer install...${NC}"
composer install --no-dev --optimize-autoloader || { echo -e "${RED}❌ Composer install échoué${NC}"; exit 1; }

# 4. Build frontend (si npm existe)
if [ -f "package.json" ]; then
    echo -e "${YELLOW}🎨 NPM build...${NC}"
    npm run build || { echo -e "${RED}❌ NPM build échoué${NC}"; exit 1; }
fi

# 5. Migration de BD (optionnel, décommenter si nécessaire)
# echo -e "${YELLOW}🗄️  Database migration...${NC}"
# php artisan migrate --force || { echo -e "${RED}❌ Migration échouée${NC}"; exit 1; }

# 6. Vider et reconstruire les caches
echo -e "${YELLOW}🧹 Nettoyage des caches...${NC}"
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

echo -e "${YELLOW}⚙️  Reconstruction des caches...${NC}"
php artisan config:cache || { echo -e "${RED}❌ Config cache échoué${NC}"; exit 1; }
php artisan route:cache || { echo -e "${RED}❌ Route cache échoué${NC}"; exit 1; }
php artisan view:cache || { echo -e "${RED}❌ View cache échoué${NC}"; exit 1; }

# 7. Vérifier les permissions
echo -e "${YELLOW}🔐 Correction des permissions...${NC}"
chmod -R 775 storage/ bootstrap/cache/ 2>/dev/null || true
chown -R www-data:www-data . 2>/dev/null || true

# 8. Redémarrer les services
echo -e "${YELLOW}🔄 Redémarrage des services...${NC}"
systemctl restart php8.4-fpm || { echo -e "${RED}❌ PHP-FPM restart échoué${NC}"; exit 1; }
systemctl reload nginx || { echo -e "${RED}❌ Nginx reload échoué${NC}"; exit 1; }

# 9. Vérifier si tout fonctionne
echo -e "${YELLOW}✅ Vérification du déploiement...${NC}"
sleep 2

# Test basique
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -I https://$(grep -m1 "server_name" /etc/nginx/sites-available/$PROJECT_NAME 2>/dev/null | awk '{print $2}' | tr -d ';') 2>/dev/null || echo "?")

if [ "$HTTP_CODE" == "200" ]; then
    echo -e "${GREEN}✅ Déploiement réussi! (HTTP $HTTP_CODE)${NC}"
else
    echo -e "${YELLOW}⚠️  HTTP $HTTP_CODE - Vérifiez les logs si nécessaire${NC}"
fi

# 10. Afficher les logs si erreur
if [ -f "storage/logs/laravel.log" ]; then
    RECENT_ERRORS=$(tail -5 storage/logs/laravel.log | grep -i error || true)
    if [ ! -z "$RECENT_ERRORS" ]; then
        echo -e "${YELLOW}📋 Dernières erreurs dans les logs:${NC}"
        echo "$RECENT_ERRORS"
    fi
fi

echo -e "${GREEN}✨ Déploiement terminé!${NC}"
