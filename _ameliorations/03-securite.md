# Phase 03 — Sécurité

> **Objectif** : Protéger le portfolio contre les abus courants sans sur-ingénierie.

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. Pas de rate limiting sur le formulaire de contact → spam/flooding possible
2. `{!! $project->problematic !!}` et autres raw HTML dans `project-detail.blade.php` → XSS possible si l'admin panel était compromis
3. Pas de headers de sécurité HTTP (CSP, X-Frame-Options, etc.)
4. Pas de honeypot sur le formulaire de contact → bots

---

## Tâches

### 03-A · Rate Limiting sur le formulaire de contact

- [ ] Dans `routes/web.php`, ajouter le middleware `throttle` sur la route contact :
  ```php
  Route::post('/contact', [PortfolioController::class, 'storeMessage'])
      ->name('contact.store')
      ->middleware('throttle:3,10'); // max 3 envois par 10 minutes par IP
  ```

- [ ] Gérer l'erreur `429 Too Many Requests` dans la vue :
  - Créer `resources/views/errors/429.blade.php` avec un message clair

**Fichiers à modifier :**
```
routes/web.php
```

**Fichiers à créer :**
```
resources/views/errors/429.blade.php
```

---

### 03-B · Honeypot anti-bot

- [ ] Ajouter un champ caché dans le formulaire de contact :
  ```html
  <!-- Honeypot : les bots remplissent ce champ, les humains non -->
  <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">
  ```

- [ ] Dans `StoreMessageRequest` (ou le contrôleur), rejeter si `website` est rempli :
  ```php
  if ($request->filled('website')) {
      return redirect()->route('home')->with('success', 'Message envoyé !');
      // On simule un succès pour ne pas alerter le bot
  }
  ```

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
app/Http/Controllers/PortfolioController.php
```

---

### 03-C · Headers de sécurité HTTP

- [ ] Créer un middleware `SecurityHeaders` :
  ```
  php artisan make:middleware SecurityHeaders
  ```

  Contenu :
  ```php
  public function handle(Request $request, Closure $next)
  {
      $response = $next($request);
      $response->headers->set('X-Content-Type-Options', 'nosniff');
      $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
      $response->headers->set('X-XSS-Protection', '1; mode=block');
      $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
      return $response;
  }
  ```

- [ ] Enregistrer dans `bootstrap/app.php` (Laravel 12) comme middleware global

**Fichiers à créer :**
```
app/Http/Middleware/SecurityHeaders.php
```

**Fichiers à modifier :**
```
bootstrap/app.php
```

---

### 03-D · Vérification du XSS dans les rich content

> ⚠️ Les champs `{!! !!}` (problematic, solution, learnings) viennent du panneau Filament admin.
> Si seul toi accèdes à l'admin, le risque est faible. Mais c'est une bonne pratique de purifier.

- [ ] Installer `ezyang/htmlpurifier` via composer OU utiliser `Str::of()->sanitize()` si disponible
- [ ] Créer un accessor dans `Project` qui retourne le HTML purifié :
  ```php
  public function getProblematicHtmlAttribute(): string
  {
      return clean($this->problematic ?? ''); // via HTMLPurifier
  }
  ```
- [ ] Utiliser `$project->problematic_html` dans la vue à la place de `$project->problematic`

**Priorité** : Faible si tu es le seul admin, mais bonne habitude.

---

## Résultat attendu

- Impossible d'envoyer plus de 3 messages en 10 minutes depuis la même IP
- Les bots classiques sont ignorés silencieusement
- Headers HTTP de sécurité présents (vérifiable sur securityheaders.com)
