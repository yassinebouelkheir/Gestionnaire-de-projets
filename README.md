# BugTracker

Application Laravel simple pour la gestion des projets, équipes, utilisateurs, commentaires, problèmes et améliorations.

## Fonctionnalités

- Authentification (inscription, connexion)
- Gestion des utilisateurs avec rôles (admin, développeur, utilisateur)
- CRUD pour projets, équipes, commentaires, problèmes et améliorations
- Middleware pour contrôler l’accès selon le rôle utilisateur
- API RESTful avec routes sécurisées

## Installation

1. Cloner le dépôt  
   `git clone https://github.com/ton-utilisateur/bugtracker.git`

2. Installer les dépendances PHP  
   `composer install`

3. Installer les dépendances JS (optionnel pour front-end)  
   `npm install && npm run dev`

4. Copier le fichier `.env` et générer la clé d’application  
   `cp .env.example .env`  
   `php artisan key:generate`

5. Configurer la base de données dans `.env`

6. Lancer les migrations  
   `php artisan migrate`

7. Lancer le serveur local  
   `php artisan serve`

## Usage

- Accéder à `http://localhost:8000/register` pour créer un compte
- Connectez-vous et gérez vos projets selon votre rôle

---

## Auteur

Ton Nom

