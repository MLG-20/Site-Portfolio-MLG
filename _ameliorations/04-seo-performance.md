# Phase 04 — SEO & Performance

> **Objectif** : Maximiser la visibilité sur Google et les réseaux sociaux, et améliorer le temps de chargement.

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. `og:url` hardcodé avec un placeholder (`https://www.tonfutursite.com/`)
2. `og:description` et `<meta description>` hardcodés, non dynamiques
3. `project-detail.blade.php` n'a aucune meta tag SEO (titre générique, pas d'OG)
4. Pas de données structurées JSON-LD (Schema.org)
5. Pas de sitemap.xml
6. Polices Google chargées via `@import` dans le CSS → bloquant pour le rendu
7. Scripts CDN externes chargés depuis `unpkg` et `cdnjs` → dépendance externe, pas de versioning
8. Pas de `<link rel="preconnect">` pour les domaines CDN

---

## Tâches

### 04-A · Corriger les meta tags dynamiques

- [ ] Dans le layout `app.blade.php` (après Phase 01), rendre les meta dynamiques :
  ```blade
  <title>@yield('meta_title', $personalInfo->name . ' | Portfolio')</title>
  <meta name="description" content="@yield('meta_description', 'Portfolio de ' . $personalInfo->name)">
  <meta property="og:title" content="@yield('og_title', $personalInfo->name . ' | Portfolio')">
  <meta property="og:description" content="@yield('og_description', $personalInfo->description)">
  <meta property="og:url" content="{{ url()->current() }}">  {{-- URL réelle --}}
  <meta property="og:image" content="@yield('og_image', asset('images/image-de-partage.jpg'))">
  <meta property="og:type" content="website">
  <link rel="canonical" href="{{ url()->current() }}">
  ```

- [ ] Dans `project-detail.blade.php`, surcharger ces sections :
  ```blade
  @section('meta_title', $project->title . ' | ' . $personalInfo->name)
  @section('meta_description', strip_tags(Str::limit($project->description, 160)))
  @section('og_title', $project->title)
  @section('og_image', Storage::url($project->image))
  ```

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php   (à créer en Phase 01)
resources/views/project-detail.blade.php
```

---

### 04-B · Ajouter des données structurées JSON-LD

- [ ] Dans le layout, ajouter un bloc JSON-LD pour le profil :
  ```html
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "{{ $personalInfo->name }}",
    "url": "{{ url('/') }}",
    "email": "{{ $personalInfo->email }}",
    "sameAs": [
      "{{ $personalInfo->linkedin }}",
      "{{ $personalInfo->github }}"
    ]
  }
  </script>
  ```

- [ ] Dans `project-detail.blade.php`, ajouter JSON-LD pour chaque projet :
  ```html
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SoftwareSourceCode",
    "name": "{{ $project->title }}",
    "url": "{{ url()->current() }}",
    "codeRepository": "{{ $project->github_link }}"
  }
  </script>
  ```

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php
resources/views/project-detail.blade.php
```

---

### 04-C · Générer un sitemap.xml

- [ ] Installer le package : `composer require spatie/laravel-sitemap`
- [ ] Créer une commande Artisan : `php artisan make:command GenerateSitemap`
- [ ] Générer `/`, et toutes les pages projets `/projets/{slug}`
- [ ] Planifier la génération dans `routes/console.php` (schedules Laravel)
- [ ] Ajouter une route pour servir le sitemap : `Route::get('/sitemap.xml', ...)`

**Packages à installer :**
```
spatie/laravel-sitemap
```

**Fichiers à créer :**
```
app/Console/Commands/GenerateSitemap.php
```

---

### 04-D · Optimiser le chargement des polices

- [ ] Remplacer le `@import` CSS par des `<link rel="preconnect">` dans le `<head>` :
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  ```
- [ ] Supprimer le `@import url(...)` en tête du fichier `style.css`

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php
public/css/style.css
```

---

### 04-E · Intégrer les scripts JS via Vite (npm)

> Objectif : supprimer les dépendances CDN fragiles et les versionner localement.

- [ ] Installer via npm :
  ```bash
  npm install scrollreveal vanilla-tilt
  ```
- [ ] Modifier `resources/js/app.js` pour importer ces librairies
- [ ] Remplacer dans le layout les `<script src="https://unpkg.com/...">` par `@vite(['resources/js/app.js'])`
- [ ] Ajouter `<link rel="preconnect" href="https://cdnjs.cloudflare.com">` ou passer FontAwesome via npm aussi

**Fichiers à modifier :**
```
resources/js/app.js
resources/views/layouts/app.blade.php
package.json
```

---

### 04-F · Mettre en cache la réponse du contrôleur index

- [ ] Activer le cache Laravel (file ou Redis) dans `.env`
- [ ] Wrapper la réponse de `index()` dans un `Cache::remember` de 1 heure
- [ ] Vider le cache automatiquement via les Observers des modèles Project/Experience

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
app/Observers/PersonalInfoObserver.php
```

---

## Résultat attendu

- Score Lighthouse SEO : 95+ (contre ~70 actuellement estimé)
- Preview correcte sur LinkedIn/Twitter/WhatsApp (OG tags)
- Google peut indexer chaque page projet individuellement
- Temps de chargement amélioré grâce aux polices non-bloquantes et scripts bundlés
