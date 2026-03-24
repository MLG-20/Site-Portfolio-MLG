# Phase 06 — Standards Frontend (CSS & JS)

> **Objectif** : Nettoyer et standardiser le code CSS/JS pour le rendre maintenable et sans bugs cachés.

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. `style.css` a deux blocs `@media (max-width: 500px)` séparés → dupliqués, contradictoires
2. `scroll-behavior: smooth` sur le sélecteur `*` est lourd pour le navigateur
3. `.project-detail-page body {}` est un sélecteur CSS invalide (imbrication incorrecte)
4. L'animation `slideInfinite` dans le `@media (max-width: 500px)` redéclare `@keyframes` (non supporté dans tous les navigateurs)
5. `window.onscroll = () => {}` écrase tout autre handler de scroll
6. VanillaTilt est chargé mais peut-être inutile sur mobile
7. Pas de `<meta name="theme-color">` pour la barre de navigation mobile
8. Polices chargées avec des poids non utilisés (300, 400, 500, 600, 700, 800, 900 → vérifier lesquels sont réellement utilisés)

---

## Tâches

### 06-A · Nettoyer les `@media` dupliqués dans style.css

- [ ] Fusionner les deux blocs `@media (max-width: 500px)` en un seul bloc
- [ ] Organiser le CSS en sections numérotées et commentées (déjà bien commencé, juste à compléter)
- [ ] Mettre toutes les `@media` queries à la fin du fichier, dans l'ordre décroissant

**Fichiers à modifier :**
```
public/css/style.css
```

---

### 06-B · Corriger le sélecteur CSS invalide

- [ ] Supprimer ou corriger `.project-detail-page body {}` (ligne 98)
  - Ce sélecteur cherche un élément `body` **à l'intérieur** d'un élément `.project-detail-page`, ce qui est impossible
  - Remplacer par `body.project-detail-page {}` pour cibler le `<body>` qui a cette classe

**Fichiers à modifier :**
```
public/css/style.css
```

---

### 06-C · Corriger `scroll-behavior` sur `*`

- [ ] Déplacer `scroll-behavior: smooth` depuis le sélecteur `*` vers uniquement `html` :
  ```css
  /* Avant */
  * { scroll-behavior: smooth; ... }

  /* Après */
  html { scroll-behavior: smooth; }
  ```

**Fichiers à modifier :**
```
public/css/style.css
```

---

### 06-D · Remplacer `window.onscroll` par `addEventListener`

- [ ] Dans `main.js`, remplacer :
  ```javascript
  // Avant
  window.onscroll = () => { ... };

  // Après
  window.addEventListener('scroll', () => { ... }, { passive: true });
  ```
- L'option `{ passive: true }` améliore les performances sur mobile en indiquant qu'on ne va pas appeler `preventDefault()`

**Fichiers à modifier :**
```
public/js/main.js
```

---

### 06-E · Désactiver VanillaTilt sur mobile

- [ ] Dans `main.js`, conditionner l'initialisation VanillaTilt :
  ```javascript
  if (typeof VanillaTilt !== 'undefined' && window.matchMedia('(hover: hover)').matches) {
      VanillaTilt.init(document.querySelectorAll(".projets-box"), { ... });
  }
  ```
- Les appareils tactiles n'ont pas de `hover`, l'effet est inutile et consomme des ressources

**Fichiers à modifier :**
```
public/js/main.js
```

---

### 06-F · Ajouter `<meta name="theme-color">`

- [ ] Ajouter dans le `<head>` :
  ```html
  <meta name="theme-color" content="#1f242d">
  ```
- [ ] Ajouter aussi `<meta name="apple-mobile-web-app-capable" content="yes">` pour iOS

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php
```

---

### 06-G · Optimiser les poids de polices chargés

- [ ] Analyser quels poids sont réellement utilisés dans le CSS
- [ ] Supprimer les poids inutilisés de l'URL Google Fonts
  - Garder uniquement : 300, 400, 600, 700, 800 (500 et 900 probablement inutilisés)

**Fichiers à modifier :**
```
resources/views/layouts/app.blade.php
```

---

### 06-H · Supprimer les `@keyframes` imbriqués dans les `@media`

Les `@keyframes` à l'intérieur d'un `@media` sont mal supportés dans certains navigateurs.

- [ ] Extraire les keyframes de l'animation `slideInfinite` du `@media (max-width: 500px)`
- [ ] Gérer la différence de `itemHeight` via une CSS variable :
  ```css
  :root { --flipper-height: 5rem; }
  @media (max-width: 500px) { :root { --flipper-height: 4rem; } }
  ```
- [ ] Utiliser `var(--flipper-height)` dans les `@keyframes`

**Fichiers à modifier :**
```
public/css/style.css
```

---

## Résultat attendu

- Score Lighthouse Performance : 85+ sur mobile
- Zéro warning CSS dans les DevTools du navigateur
- Scroll fluide sans impact sur les performances
- Code CSS/JS lisible et sans duplication
