# Phase 08 — Tests

> **Objectif** : Ajouter une couverture de tests minimale pour éviter les régressions lors des futures modifications.

**Statut global** : `[ ]` À faire

---

## Contexte

Le projet n'a actuellement aucun test. Laravel 12 inclut Pest par défaut. L'objectif n'est pas 100% de couverture, mais de couvrir les chemins critiques.

---

## Tâches

### 08-A · Tests des routes principales (Feature Tests)

- [ ] Créer `tests/Feature/PortfolioTest.php`

  Tests à écrire :
  ```php
  it('affiche la page d\'accueil', function () {
      $this->get('/')->assertStatus(200)->assertSee($personalInfo->name);
  });

  it('affiche la page détail d\'un projet', function () {
      $project = Project::factory()->create();
      $this->get("/projets/{$project->id}")->assertStatus(200)->assertSee($project->title);
  });

  it('retourne 404 pour un projet inexistant', function () {
      $this->get('/projets/99999')->assertStatus(404);
  });
  ```

**Fichiers à créer :**
```
tests/Feature/PortfolioTest.php
```

---

### 08-B · Tests du formulaire de contact

- [ ] Créer `tests/Feature/ContactFormTest.php`

  Tests à écrire :
  ```php
  it('envoie un message valide', function () {
      $this->post('/contact', [
          'name'    => 'Test User',
          'email'   => 'test@example.com',
          'subject' => 'Test',
          'message' => 'Ceci est un message de test.',
      ])->assertRedirect()->assertSessionHas('success');
  });

  it('rejette un message sans email', function () {
      $this->post('/contact', [
          'name'    => 'Test',
          'email'   => '',
          'subject' => 'Test',
          'message' => 'Message',
      ])->assertSessionHasErrors('email');
  });

  it('rejette le spam via rate limiting', function () {
      for ($i = 0; $i < 4; $i++) {
          $this->post('/contact', [...]);
      }
      $this->post('/contact', [...])->assertStatus(429);
  });
  ```

**Fichiers à créer :**
```
tests/Feature/ContactFormTest.php
```

---

### 08-C · Factories pour les modèles

- [ ] Créer `database/factories/PersonalInfoFactory.php`
- [ ] Créer `database/factories/ProjectFactory.php`
- [ ] Créer `database/factories/ExperienceFactory.php`

  Exemple :
  ```php
  // ProjectFactory
  public function definition(): array
  {
      return [
          'title'       => fake()->sentence(3),
          'description' => fake()->paragraph(),
          'image'       => 'projects/placeholder.jpg',
          'tags'        => ['Laravel', 'PHP'],
      ];
  }
  ```

**Fichiers à créer :**
```
database/factories/PersonalInfoFactory.php
database/factories/ProjectFactory.php
database/factories/ExperienceFactory.php
```

---

### 08-D · Test de l'envoi d'email

- [ ] Utiliser `Mail::fake()` pour tester que l'email est envoyé sans réellement l'envoyer :
  ```php
  it('envoie une notification email lors d\'un nouveau message', function () {
      Mail::fake();
      $this->post('/contact', [...valid data...]);
      Mail::assertSent(NewContactMessage::class);
  });
  ```

---

## Commandes utiles

```bash
# Lancer tous les tests
php artisan test

# Lancer un fichier de test spécifique
php artisan test tests/Feature/ContactFormTest.php

# Avec couverture de code (nécessite Xdebug)
php artisan test --coverage
```

---

## Résultat attendu

- ~10-15 tests couvrant les chemins critiques
- Confiance pour refactorer sans casser les fonctionnalités existantes
- CI/CD peut lancer les tests automatiquement avant chaque déploiement
