# Phase 05 — UX & Accessibilité

> **Objectif** : Rendre le portfolio utilisable par tous (dont les personnes avec lecteur d'écran), et améliorer l'expérience sur les cas limites (erreurs, états de chargement, pages manquantes).

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. Les liens de réseaux sociaux n'ont pas de `aria-label` → illisibles par les lecteurs d'écran
2. Pas de page 404 personnalisée
3. Le formulaire de contact n'a pas d'état de chargement → double soumission possible
4. Le formulaire ne conserve pas les valeurs remplies si la validation échoue
5. Le champ téléphone utilise `type="number"` → clavier numérique limité, pas de `+`, mauvaise UX
6. La section experience n'affiche pas le nom de l'entreprise/école
7. `project-detail.blade.php` n'a ni favicon, ni script, ni bouton retour-en-haut, ni footer
8. Pas de lien "skip to content" pour l'accessibilité clavier
9. L'animation flipper est hardcodée pour 3 titres, casse si l'admin en met 4+

---

## Tâches

### 05-A · Accessibilité des icônes et liens

- [ ] Ajouter `aria-label` sur chaque lien de réseau social :
  ```blade
  <a href="{{ $personalInfo->linkedin }}" target="_blank" aria-label="Profil LinkedIn de {{ $personalInfo->name }}" rel="noopener noreferrer">
  ```
- [ ] Ajouter `rel="noopener noreferrer"` sur tous les liens `target="_blank"`
- [ ] Ajouter un lien "Aller au contenu principal" en haut du body (skip navigation) :
  ```html
  <a href="#main-content" class="skip-link">Aller au contenu principal</a>
  ```
- [ ] Ajouter `id="main-content"` sur la première `<section>`

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
resources/views/project-detail.blade.php
public/css/style.css    (style du .skip-link)
```

---

### 05-B · Créer une page 404 personnalisée

- [ ] Créer `resources/views/errors/404.blade.php`
  - Reprend le layout du portfolio
  - Message amical avec lien retour vers l'accueil
  - Cohérent avec le design dark/bleu

**Fichiers à créer :**
```
resources/views/errors/404.blade.php
```

---

### 05-C · Améliorer le formulaire de contact

- [ ] Changer `type="number"` → `type="tel"` pour le téléphone
- [ ] Ajouter `value="{{ old('name') }}"` sur tous les inputs pour repeupler après erreur
- [ ] Ajouter `@error('champ')` sous chaque champ pour afficher les messages d'erreur
- [ ] Désactiver le bouton submit en JS pendant l'envoi pour éviter le double clic :
  ```javascript
  form.addEventListener('submit', function() {
      submitBtn.disabled = true;
      submitBtn.value = 'Envoi en cours...';
  });
  ```
- [ ] Ajouter `aria-describedby` sur les inputs pour lier aux messages d'erreur

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
public/js/main.js
public/css/style.css    (style des erreurs de validation)
```

---

### 05-D · Afficher le nom de l'entreprise dans les expériences

- [ ] Vérifier si `Experience` a un champ `company` en base de données
  - Si oui : l'ajouter dans la vue `welcome.blade.php` sous `$exp->title`
  - Si non : créer une migration `add_company_to_experiences_table`

**Fichiers à vérifier :**
```
database/migrations/    (chercher la migration experiences)
app/Models/Experience.php
resources/views/welcome.blade.php
```

---

### 05-E · Compléter `project-detail.blade.php`

- [ ] Ajouter favicon dans le `<head>`
- [ ] Ajouter Google Analytics (ou extraire dans le layout après Phase 01)
- [ ] Ajouter un bouton "Retour en haut" ou lien retour vers le portfolio
- [ ] Ajouter un footer minimaliste avec copyright
- [ ] Ajouter les scripts ScrollReveal/VanillaTilt si nécessaire pour cette page

> Note : Ces points disparaissent automatiquement une fois la Phase 01 (layouts) réalisée.

---

### 05-F · Rendre l'animation flipper dynamique

Le CSS de l'animation `slideInfinite` est hardcodé pour exactement 3 titres :
```css
75%, 95% { transform: translateY(-15rem); }  /* Mot 3 = -5rem * 3 */
```

- [ ] Injecter dynamiquement les keyframes via JS selon le nombre réel de titres :
  ```javascript
  function createFlipperAnimation(itemCount, itemHeight) {
      const step = 100 / itemCount;
      // Générer dynamiquement @keyframes slideInfinite
  }
  ```
- [ ] Ou passer le nombre de titres en `data-count` sur l'élément et calculer le CSS via JS

**Fichiers à modifier :**
```
public/js/main.js
public/css/style.css    (supprimer les keyframes hardcodés)
resources/views/welcome.blade.php
```

---

## Résultat attendu

- Score Lighthouse Accessibilité : 90+
- Formulaire de contact robuste : erreurs visibles, champs conservés, anti-double-submit
- Expérience cohérente sur la page projet (même design que l'accueil)
- Animation flipper qui fonctionne quel que soit le nombre de titres configurés
