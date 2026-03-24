# Phase 01 — Architecture Blade

> **Objectif** : Éliminer la duplication de code entre les vues en introduisant les layouts Blade et les composants. C'est la base sur laquelle toutes les autres phases s'appuient.

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. `welcome.blade.php` et `project-detail.blade.php` dupliquent entièrement le `<head>`, les scripts et le footer.
2. La logique PHP de décodage des `titles` et `tags` est dans les vues — elle appartient aux Models.
3. Pas de Blade components pour les éléments réutilisables (boutons, tags, cartes projet).

---

## Tâches

### 01-A · Créer le layout principal

- [ ] Créer `resources/views/layouts/app.blade.php`
  - Contient : `<head>` complet, header, footer, scripts
  - Utilise `@yield('title')`, `@yield('content')`, `@stack('scripts')`
  - Déplacer Google Analytics dans ce layout
  - Déplacer les `<link>` CSS dans ce layout

**Fichiers à créer :**
```
resources/views/layouts/app.blade.php
```

**Fichiers à modifier :**
```
resources/views/welcome.blade.php         → @extends('layouts.app') + @section('content')
resources/views/project-detail.blade.php  → @extends('layouts.app') + @section('content')
```

---

### 01-B · Déplacer la logique des vues vers les Models

- [ ] Dans `app/Models/Project.php` : s'assurer que `tags` est casté en `array` via `$casts`
  - Supprimer le `@php` inline dans `project-detail.blade.php`
- [ ] Dans `app/Models/PersonalInfo.php` : s'assurer que `titles` est casté en `array` via `$casts`
  - Supprimer le bloc `@php` inline dans `welcome.blade.php`
  - Ajouter un accessor `getTitlesAttribute()` si nécessaire comme filet de sécurité

**Fichiers à modifier :**
```
app/Models/Project.php
app/Models/PersonalInfo.php
resources/views/welcome.blade.php
resources/views/project-detail.blade.php
```

---

### 01-C · Créer des Blade Components

- [ ] Créer `resources/views/components/project-card.blade.php`
  - Extraire le bloc `.projets-box` de `welcome.blade.php`
  - Props : `$project`

- [ ] Créer `resources/views/components/tag.blade.php`
  - Extraire `<span class="tag">` de `project-detail.blade.php`
  - Props : `$label`

- [ ] Créer `resources/views/components/btn.blade.php`
  - Extraire le pattern `<a class="btn">` réutilisé partout
  - Props : `$href`, `$label`, `$target = '_self'`, `$download = false`

**Fichiers à créer :**
```
resources/views/components/project-card.blade.php
resources/views/components/tag.blade.php
resources/views/components/btn.blade.php
```

---

### 01-D · Corriger le bug HTML cassé

- [ ] Corriger `<h3>Bonjour, je suis </h>` → `<h3>Bonjour, je suis </h3>`
  - Fichier : `resources/views/welcome.blade.php` ligne 47

---

## Résultat attendu

Après cette phase :
- `welcome.blade.php` fait ~60 lignes (contre 172 actuellement)
- `project-detail.blade.php` fait ~40 lignes (contre 72 actuellement)
- Zéro logique PHP dans les vues
- Ajout d'un élément réutilise = modifier 1 fichier au lieu de 2+
