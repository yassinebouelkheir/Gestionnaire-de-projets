# Gestionnaire de projets

Gestionnaire de projets construit avec **Laravel** (backend) et un front bâti avec **Vite**/**Tailwind CSS**.  
Il permet de gérer les **projets**, **équipes**, **utilisateurs**, **commentaires**, **problèmes** et **améliorations**, avec un **RBAC** (rôles) et des **APIs REST** sécurisées.

## ✨ Fonctionnalités

- Authentification (inscription, connexion, session).
- Rôles utilisateurs (admin, développeur, utilisateur) et **middleware** d’accès.
- CRUD complet : projets, équipes, commentaires, problèmes, améliorations.
- API RESTful avec routes protégées (auth + rôles).
- Frontend moderne via **Vite**

## 🗂️ Structure du projet

- `app/` — Code applicatif Laravel (models, controllers, policies, etc.).  
- `bootstrap/` — Bootstrap de l’app Laravel.  
- `config/` — Fichiers de configuration.  
- `database/` — Migrations (et éventuellement seeders/factories).  
- `public/` — Entrée web (index.php, assets compilés).  
- `resources/` — Vues Blade, assets front.  
- `routes/` — Définition des routes web/api.  
- `storage/` — Logs, fichiers générés, cache.  
- `tests/` — Tests automatisés.  
- Outils front : `tailwind.config.js`, `postcss.config.js`, `vite.config.js`.

## 🚀 Prérequis

- **PHP** 8.1+ (recommandé), **Composer**  
- **Node.js** 18+ et **npm** (pour le front)  

## 🔧 Installation & démarrage

1) **Cloner le dépôt**
```bash
git clone https://github.com/yassinebouelkheir/Gestionnaire-de-projets.git
cd Gestionnaire-de-projets
```

2) **Installer les dépendances PHP**
```bash
composer install
```

3) **Installer les dépendances front (optionnel mais recommandé)**
```bash
npm install
# Pour le dev (HMR) :
npm run dev
# Ou build de prod :
npm run build
```

4) **Configurer l’environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5) **Migrations (et éventuellement seeds)**
```bash
php artisan migrate
# Si tu ajoutes des seeders/factories :
# php artisan db:seed
```

6) **Lancer le serveur**
```bash
php artisan serve
```
L’application sera accessible par défaut sur `http://localhost:8000/`.

## 🔐 Comptes & rôles

Le système gère au moins trois rôles : **admin**, **développeur**, **utilisateur**, avec un contrôle d’accès par **middleware**. La création des utilisateurs/affectation des rôles se fait via l’UI ou les seeders (si présents).

## 🧭 Routes & API

- Les routes **web** et **API** se trouvent dans `routes/`.  
- Les endpoints API sont protégés (auth + rôles). Pense à inclure un jeton ou une session selon la config.

## 🧪 Tests

Exécuter la suite de tests :
```bash
php artisan test
# ou
./vendor/bin/phpunit
```

## 🧰 Outils de build front

Le projet inclut **Vite** (`vite.config.js`).  
Pendant le développement : `npm run dev`. Pour la production : `npm run build`.

## 📦 Déploiement (pistes rapides)

- Utilise `php artisan config:cache` et `route:cache` en prod.  
- Serre `public/` comme racine web.  
- Configure le worker queue si tu utilises des jobs.  
- Gère le build front (`npm run build`) et sers les assets générés.

## 🙌 Auteur

[@yassinebouelkheir](https://github.com/yassinebouelkheir)
