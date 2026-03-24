# Phase 07 — Nouvelles Fonctionnalités

> **Objectif** : Ajouter des fonctionnalités qui enrichissent l'expérience visiteur et différencient le portfolio. À aborder uniquement après les phases 01-05.

**Statut global** : `[x]` Terminé (07-A et 07-B — 2026-03-23)

---

## Fonctionnalités prioritaires

### 07-A · Filtrage des projets par technologie

- [ ] Ajouter des boutons de filtre au-dessus de la grille de projets
  ```html
  <div class="projets-filtres">
      <button class="filtre-btn active" data-filtre="tous">Tous</button>
      <button class="filtre-btn" data-filtre="laravel">Laravel</button>
      <!-- Générés dynamiquement depuis les tags en base -->
  </div>
  ```
- [ ] Générer les boutons dynamiquement en Blade depuis la liste unique des tags de tous les projets
- [ ] Ajouter `data-tags="{{ implode(',', $projet->tags) }}"` sur chaque `.projets-box`
- [ ] Implémenter le filtrage en JavaScript (afficher/masquer avec animation)

**Approche** : JavaScript pur, sans rechargement de page.

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
public/js/main.js
public/css/style.css
```

---

### 07-B · Mode sombre / clair (toggle)

> Le portfolio est déjà dark. Ajouter un toggle pour un thème clair optionnel.

- [ ] Ajouter un bouton toggle dans le header (icône soleil/lune)
- [ ] Définir les variables CSS pour le thème clair dans `style.css` :
  ```css
  [data-theme="light"] {
      --bg-color: #f0f4f8;
      --second-bg-color: #dde4ee;
      --text-color: #1f242d;
  }
  ```
- [ ] En JS, basculer `document.documentElement.setAttribute('data-theme', 'light')`
- [ ] Sauvegarder la préférence dans `localStorage`
- [ ] Respecter `prefers-color-scheme` par défaut

**Fichiers à modifier :**
```
public/css/style.css
public/js/main.js
resources/views/layouts/app.blade.php
```

---

### 07-C · Section "À propos" dédiée ✅ Terminée (2026-03-23)

- [x] Migration : `about`, `skills`, `languages`, `availability` ajoutés à `personal_infos`
- [x] Modèle `PersonalInfo` : casts `skills` et `languages` en array
- [x] Filament admin : 4 nouveaux champs dans `PersonalInfoResource`
- [x] Section `#a-propos` ajoutée entre `#accueil` et `#experience`
- [x] Menu de navigation mis à jour (lien "À propos")

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
public/css/style.css
database/migrations/    (si nouveau champ nécessaire)
app/Filament/Resources/PersonalInfoResource.php
```

---

### 07-D · Pagination ou "Voir plus" pour les projets

> Si le nombre de projets grandit, la page deviendra trop longue.

- [ ] Afficher les 6 projets les plus récents par défaut
- [ ] Ajouter un bouton "Voir tous les projets" qui charge les suivants via AJAX ou redirige vers une page `/projets`
- [ ] **Option simple** : `Project::latest()->take(6)->get()` dans le contrôleur
- [ ] **Option avancée** : route `/projets` avec pagination complète

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
resources/views/welcome.blade.php
routes/web.php    (si nouvelle route)
```

---

### 07-E · Compteur de vues sur les projets

- [ ] Ajouter une colonne `views_count` dans la table `projects`
- [ ] Incrémenter dans `showProject()` : `$project->increment('views_count')`
- [ ] Afficher dans la page détail et dans Filament admin
- [ ] Utiliser un cookie pour ne compter qu'une vue par utilisateur par 24h

**Fichiers à modifier :**
```
database/migrations/    (nouvelle migration)
app/Models/Project.php
app/Http/Controllers/PortfolioController.php
resources/views/project-detail.blade.php
```

---

### 07-F · Bouton de contact flottant (WhatsApp / Email)

- [ ] Ajouter un bouton flottant en bas à gauche (WhatsApp ou Email direct)
- [ ] Visible en permanence sur toutes les pages

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php
public/css/style.css
```

---

## Fonctionnalités optionnelles (backlog)

| Idée | Effort | Valeur |
|------|--------|--------|
| Blog/articles techniques | Élevé | Haute |
| Témoignages/recommandations | Moyen | Haute |
| Timeline interactive de carrière | Moyen | Moyenne |
| Chat en temps réel (Reverb/Pusher) | Élevé | Basse |
| PWA (installable sur mobile) | Moyen | Moyenne |
| Internationalisation FR/EN | Moyen | Haute si ciblé international |
