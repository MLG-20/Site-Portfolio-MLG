# Site Portfolio MLG

Portfolio personnel développé avec Laravel 12, présentant mes projets, expériences et compétences. Administrable via un panneau Filament, avec formulaire de contact et génération de sitemap automatique.

## Fonctionnalités

- Page d'accueil avec présentation, expériences et projets filtrables par tags
- Page de détail par projet avec compteur de vues (1 vue / visiteur / 24h)
- Formulaire de contact avec envoi d'email et validation
- Panneau d'administration Filament (projets, expériences, infos personnelles)
- Génération de sitemap XML via commande Artisan
- Headers de sécurité (CSP, X-Frame-Options, etc.)
- Cache 24h sur les données publiques

## Stack technique

- **Backend** : PHP 8.2, Laravel 12
- **Admin** : Filament 3
- **Frontend** : Blade, CSS personnalisé, JavaScript vanilla
- **Icônes** : FontAwesome (via Blade)
- **SEO** : spatie/laravel-sitemap
- **Base de données** : SQLite

## Installation

```bash
# Cloner le dépôt
git clone https://github.com/MLG-20/Site-Portfolio-MLG.git
cd Site-Portfolio-MLG

# Installation complète (composer, .env, migrations, npm build)
composer run setup
```

Ou étape par étape :

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

## Configuration

Copier `.env.example` en `.env` et ajuster les variables :

```env
APP_URL=http://localhost
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_FROM_ADDRESS=...
PORTFOLIO_ADMIN_EMAIL=votre@email.com
```

## Lancement en développement

```bash
composer run dev
```

Lance en parallèle : serveur PHP, queue, logs (Pail) et Vite.

## Commandes utiles

```bash
# Générer le sitemap
php artisan sitemap:generate

# Lancer les tests
composer run test

# Accéder au panel admin
/admin
```

## Structure principale

```
app/
├── Console/Commands/       # GenerateSitemap
├── Filament/Resources/     # ProjectResource, ExperienceResource, PersonalInfoResource
├── Http/
│   ├── Controllers/        # PortfolioController
│   ├── Middleware/         # SecurityHeaders
│   └── Requests/           # StoreMessageRequest
├── Mail/                   # NewContactMessage
├── Models/                 # Project, Experience, PersonalInfo, Message
└── Observers/              # ProjectObserver, ExperienceObserver
resources/views/
├── components/             # project-card, tag
├── emails/                 # contact
├── errors/                 # 404, 429
├── layouts/                # app
├── project-detail.blade.php
└── welcome.blade.php
```

## Licence

Projet personnel — tous droits réservés.
