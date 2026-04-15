# 🖥️ Récap VPS Contabo — Ahmed MLG
**Ahmed (MLG-20) | mio-ressources.me + laminegueye.dev**

---

## 🔐 Connexion SSH
```bash
ssh root@167.86.94.135
```

---

## 📁 Dossiers des projets

### MIO Ressources
```bash
cd /var/www/mio-ressources-site
```

### Portfolio
```bash
cd /var/www/portfolio
```

---

## 🔄 Déployer une mise à jour

### Option 1: Depuis ta machine (SSH)
```bash
ssh root@167.86.94.135 "cd /var/www/portfolio && bash ./deploy.sh"
ssh root@167.86.94.135 "cd /var/www/mio-ressources-site && bash ./deploy.sh"
```

### Option 2: Depuis le VPS (PLUS SIMPLE ✨)
```bash
# Portfolio
cd /var/www/portfolio
./deploy.sh          # ou: ./deploy.sh develop (pour une autre branche)

# MIO Ressources
cd /var/www/mio-ressources-site
./deploy.sh          # ou: ./deploy.sh develop
```

### Déploiement manuel (si besoin)

**Portfolio:**
```bash
cd /var/www/portfolio
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
systemctl restart php8.4-fpm
systemctl reload nginx
```

**MIO Ressources:**
```bash
cd /var/www/mio-ressources-site
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
systemctl restart php8.4-fpm
systemctl reload nginx
```

---

## ⚙️ Services essentiels

### Nginx
```bash
systemctl status nginx
systemctl restart nginx
systemctl reload nginx
nginx -t  # tester la config
```

### PHP 8.4 FPM
```bash
systemctl status php8.4-fpm
systemctl restart php8.4-fpm
```

### MySQL
```bash
systemctl status mysql
systemctl restart mysql
mysql -u root -pMioRessources@2026!
```

---

## 🗂️ Fichiers importants

| Fichier | Chemin |
|---|---|
| `.env` MIO | `/var/www/mio-ressources-site/.env` |
| `.env` Portfolio | `/var/www/portfolio/.env` |
| Config Nginx MIO | `/etc/nginx/sites-available/mio-ressources` |
| Config Nginx Portfolio | `/etc/nginx/sites-available/portfolio` |
| Logs Laravel MIO | `/var/www/mio-ressources-site/storage/logs/laravel.log` |
| Logs Laravel Portfolio | `/var/www/portfolio/storage/logs/laravel.log` |
| Logs Nginx | `/var/log/nginx/error.log` |
| Deploy script | `/opt/deploy.sh` |

---

## 🐛 Déboguer une erreur

### Voir les logs Laravel
```bash
tail -50 /var/www/mio-ressources-site/storage/logs/laravel.log
```

### Voir les logs Nginx
```bash
tail -50 /var/log/nginx/error.log
```

### Vider tous les caches
```bash
cd /var/www/mio-ressources-site
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🔒 SSL (Let's Encrypt)
```bash
# Renouveler manuellement
certbot renew

# Vérifier expiration
certbot certificates
```
SSL expire le **10 juillet 2026** — renouvellement automatique configuré.

---

## 📊 Surveiller le serveur
```bash
# CPU / RAM
htop

# Espace disque
df -h

# Processus PHP
ps aux | grep php
```

---

## 🚨 En cas de panne

| Erreur | Solution |
|---|---|
| 502 Bad Gateway | `systemctl restart php8.4-fpm` |
| 504 Gateway Timeout | `systemctl restart php8.4-fpm nginx` |
| Site inaccessible | `systemctl restart nginx` |
| Erreur DB | `systemctl restart mysql` |

---

## 🌐 Infos importantes

| Info | Valeur |
|---|---|
| IP VPS | `167.86.94.135` |
| **Domaine 1** | `mio-ressources.me` |
| **Domaine 2** | `laminegueye.dev` (+ www.) |
| PHP | 8.4 |
| Laravel | 12 |
| OS | Ubuntu 24.04 LTS |
| Hébergeur | Contabo |
| Deploy | Utiliser `deploy.sh` |

---

## 📚 Quick Commands

```bash
# Voir l'état des deux projets
curl -I https://mio-ressources.me
curl -I https://laminegueye.dev

# Vérifier les logs rapidement
tail -f /var/www/portfolio/storage/logs/laravel.log
tail -f /var/www/mio-ressources-site/storage/logs/laravel.log

# Vider les caches (Portfolio)
cd /var/www/portfolio && php artisan cache:clear && php artisan config:clear

# Vider les caches (MIO)
cd /var/www/mio-ressources-site && php artisan cache:clear && php artisan config:clear
```

---

*Généré le 15 avril 2026 — MLG-20*

---

## 🤖 Guide du script deploy.sh

### Auto-détection du projet ✨

Le script détecte **automatiquement** quel projet tu déploies selon ton dossier courant :

```bash
# Depuis /var/www/portfolio
cd /var/www/portfolio
./deploy.sh              # ✅ Détecte = portfolio
./deploy.sh develop      # ✅ Branche develop, projet = portfolio

# Depuis /var/www/mio-ressources-site
cd /var/www/mio-ressources-site
./deploy.sh              # ✅ Détecte = mio-ressources-site
./deploy.sh main         # ✅ Branche main, projet = mio-ressources-site
```

### Features du script
✅ **Auto-détection du projet**  
✅ Git pull automatique  
✅ Composer + NPM build  
✅ Caches (config, routes, views)  
✅ Permissions corrigées  
✅ Services reloadés  
✅ Vérification HTTP 200  
✅ Logs affichés si erreurs  

**Note:** Le script arrête tout si une étape échoue (sécurité).
