# Phase 02 — Standards Backend Laravel

> **Objectif** : Appliquer les bonnes pratiques Laravel : config propre, Form Requests, Mailable, cache, et suppression du code mort.

**Statut global** : `[ ]` À faire

---

## Problèmes actuels

1. Email admin hardcodé dans le contrôleur (`mlamine.gueye1@univ-thies.sn`)
2. `Mail::raw()` utilisé au lieu d'une classe `Mailable` dédiée
3. `catch (\Exception $e) { }` avale silencieusement les erreurs (aucun log)
4. Validation dans le contrôleur directement → doit être dans un `Form Request`
5. `PersonalInfo::latest()->first()` appelé dans chaque méthode du contrôleur sans cache
6. Redirect avec `#contact` ne fonctionne pas (`redirect()->route('home', '#contact')` ignore le fragment)
7. Le contrôleur `showProject` charge `PersonalInfo` inutilement si la vue ne l'utilise pas vraiment

---

## Tâches

### 02-A · Passer les configs dans `.env`

- [ ] Ajouter dans `.env` :
  ```
  ADMIN_EMAIL=mlamine.gueye1@univ-thies.sn
  MAIL_FROM_ADDRESS=noreply@tonfutursite.com
  MAIL_FROM_NAME="Portfolio MLG"
  ```

- [ ] Créer `config/portfolio.php` :
  ```php
  return [
      'admin_email' => env('ADMIN_EMAIL'),
  ];
  ```

- [ ] Remplacer dans `PortfolioController` :
  ```php
  // Avant
  $adminEmail = 'mlamine.gueye1@univ-thies.sn';

  // Après
  $adminEmail = config('portfolio.admin_email');
  ```

**Fichiers à créer :**
```
config/portfolio.php
```

**Fichiers à modifier :**
```
.env
.env.example         (ajouter les nouvelles variables)
app/Http/Controllers/PortfolioController.php
```

---

### 02-B · Créer un Form Request pour le contact

- [ ] Générer : `php artisan make:request StoreMessageRequest`
- [ ] Déplacer les règles de validation depuis `storeMessage()`
- [ ] Ajouter `type="tel"` dans la vue (corrige aussi le bug)
- [ ] Ajouter validation du téléphone : `'phone' => 'nullable|regex:/^[0-9\s\+\-\(\)]{7,20}$/'`
- [ ] Ajouter validation anti-spam sur le message : `'message' => 'required|min:10|max:2000'`

**Fichiers à créer :**
```
app/Http/Requests/StoreMessageRequest.php
```

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
resources/views/welcome.blade.php   (type="tel", affichage des erreurs)
```

---

### 02-C · Créer une classe Mailable

- [ ] Générer : `php artisan make:mail NewContactMessage --markdown=emails.contact`
- [ ] Créer le template `resources/views/emails/contact.blade.php`
- [ ] Remplacer `Mail::raw(...)` par `Mail::to(...)->send(new NewContactMessage($message))`
- [ ] Corriger le `catch` : ajouter `Log::error('Mail contact failed: ' . $e->getMessage());`

**Fichiers à créer :**
```
app/Mail/NewContactMessage.php
resources/views/emails/contact.blade.php
```

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
```

---

### 02-D · Mettre en cache PersonalInfo

- [ ] Utiliser `Cache::remember()` dans le contrôleur :
  ```php
  $personalInfo = Cache::remember('personal_info', now()->addHours(24), function () {
      return PersonalInfo::latest()->first();
  });
  ```
- [ ] Vérifier que `PersonalInfoObserver` vide bien ce cache sur update (clé `personal_info`)

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
app/Observers/PersonalInfoObserver.php
```

---

### 02-E · Corriger le redirect après contact

- [ ] Remplacer :
  ```php
  return redirect()->route('home', '#contact')->with('success', '...');
  ```
  Par :
  ```php
  return redirect()->to(route('home') . '#contact')->with('success', '...');
  ```

**Fichiers à modifier :**
```
app/Http/Controllers/PortfolioController.php
```

---

### 02-F · Afficher les erreurs de validation dans la vue

- [ ] Dans `welcome.blade.php`, section contact, ajouter après chaque `<input>` :
  ```blade
  @error('name') <span class="form-error">{{ $message }}</span> @enderror
  ```
- [ ] Repeupler les champs avec `value="{{ old('name') }}"`
- [ ] Ajouter le CSS pour `.form-error` (rouge, petite taille)

**Fichiers à modifier :**
```
resources/views/welcome.blade.php
public/css/style.css
```

---

## Résultat attendu

- Zéro valeur hardcodée dans le code
- Erreurs d'envoi d'email loggées dans `storage/logs/laravel.log`
- Formulaire de contact affiche les erreurs de validation proprement
- Email formaté en HTML via le template Mailable
