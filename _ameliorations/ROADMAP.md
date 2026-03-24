# Roadmap d'Amélioration — Portfolio MLG

> Chaque phase doit être terminée et testée avant de passer à la suivante.
> Statuts : `[ ]` À faire · `[~]` En cours · `[x]` Terminé

---

## Vue d'ensemble des phases

| Phase | Titre | Priorité | Statut |
|-------|-------|----------|--------|
| [01](./01-architecture-blade.md) | Architecture Blade (layouts & composants) | 🔴 Critique | [x] |
| [02](./02-backend-standards.md) | Standards Backend (Laravel best practices) | 🔴 Critique | [x] |
| [03](./03-securite.md) | Sécurité | 🔴 Critique | [x] |
| [04](./04-seo-performance.md) | SEO & Performance | 🟠 Haute | [x] |
| [05](./05-ux-accessibilite.md) | UX & Accessibilité | 🟠 Haute | [x] |
| [06](./06-frontend-standards.md) | Standards Frontend (CSS/JS) | 🟡 Moyenne | [x] |
| [07](./07-fonctionnalites.md) | Nouvelles Fonctionnalités | 🟢 Basse | [x] |
| [08](./08-tests.md) | Tests | 🟡 Moyenne | [ ] |

---

## Bugs critiques — ✅ Tous corrigés le 2026-03-22

- [x] **Tag HTML cassé** : `</h>` → `</h3>` (`welcome.blade.php`)
- [x] **Email admin hardcodé** → déplacé dans `.env` + `config/portfolio.php`
- [x] **OG URL placeholder** → remplacé par `{{ url('/') }}`
- [x] **`input type="number"`** → `type="tel"`
- [x] **CSS règle morte** → `body.project-detail-page {}` corrigé
- [x] **Erreur silencieuse** → `Log::error(...)` ajouté dans le catch
- [x] **`scroll-behavior` sur `*`** → déplacé sur `html` uniquement
- [x] **Deux `@media 500px` dupliqués** → fusionnés en un seul bloc
- [x] **`window.onscroll`** → `addEventListener('scroll', ..., { passive: true })`
- [x] **Redirect fragment `#contact`** → `redirect()->to(route('home') . '#contact')`

---

## Journal de progression

| Date | Phase | Action effectuée |
|------|-------|-----------------|
| 2026-03-22 | 01→06 | Architecture, Backend, Sécurité, SEO, UX, Frontend |
| 2026-03-23 | 07-A | Filtre projets par tag — boutons dynamiques Blade + JS |
| 2026-03-23 | 07-B | Mode clair/sombre — CSS variables + toggle header + localStorage |
