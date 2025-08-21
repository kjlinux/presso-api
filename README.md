# Presso API - Backend Laravel

Une API REST développée avec Laravel pour la gestion des transactions avec authentification JWT sécurisée.

## Dépôt GitHub

https://github.com/kjlinux/presso-api

## Objectifs Réalisés

-   **API REST** avec authentification JWT
-   **Module Transactions** : consultation et création de transactions
-   **Tests unitaires** avec Pest
-   **Base de données PostgreSQL**

## Stack Technique

-   **Framework** : Laravel
-   **Base de données** : PostgreSQL
-   **Authentification** : JWT (JSON Web Tokens)
-   **Tests** : Pest
-   **Documentation** : Endpoints REST

## Choix Techniques

### Pourquoi Laravel ?

Laravel a été choisi comme framework backend pour plusieurs raisons stratégiques :

-   **Développement rapide** : Artisan CLI, migrations, seeders et ORM Eloquent
-   **Sécurité intégrée** : Protection CSRF, validation des données
-   **Architecture MVC claire**
-   **Intégration JWT native**
-   **Tests intégrés** : Pest facilite l'implémentation des tests
-   **Compatibilité PostgreSQL** : Support natif et optimisé pour PostgreSQL

## Note sur le Frontend

En raison de problèmes de compatibilité avec les versions des packages utilisés dans le template Figma et npm, il est difficile de démarrer un projet React avec TypeScript sur la base du code fourni par Figma. Cette implémentation se concentre donc exclusivement sur l'API backend.

## Prérequis

-   **PHP** : Version 8.2 minimum
-   **PostgreSQL** : Version récente
-   **Composer** : Pour la gestion des dépendances PHP

## Installation

### Option 1 : Installation locale

1. **Cloner le projet**

```bash
git clone https://github.com/kjlinux/presso-api.git
cd presso-api
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configuration de l'environnement**

```bash
cp .env.example .env
```

4. **Configurer la base de données dans le fichier `.env`**

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=presso_api
DB_USERNAME=votre_nom_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

5. **Générer les clés de sécurité**

```bash
php artisan key:generate
php artisan jwt:secret
```

6. **Créer la base de données PostgreSQL**
   Créer une base de données nommée `presso_api` dans votre instance PostgreSQL une base de données nommée `test` dans votre instance MySQL.

7. **Exécuter les migrations**

```bash
php artisan migrate --seed
```

8. **Lancer le serveur de développement**

```bash
php artisan serve
```

## Test avec Postman

Une fois le serveur lancé, vous pouvez démarrer postman sur votre ordinateur et importer le fichier `collection.json` pour effectuer les tests api à défaut du frontend qui n'est pas fonctionnel.

## Endpoints Disponibles

### Authentification

-   **POST** `/api/auth/login` - Connexion utilisateur

### Transactions

-   **GET** `/api/transactions` - Lister toutes les transactions
-   **POST** `/api/transactions` - Créer une nouvelle transaction

## Tests

Exécuter les tests unitaires avec Pest :

```bash
php artisan test
```

Veuillez à commenter les lignes 12 et 17 du fichier ./routes/api.php avant de lancer les tests.

## Authentification JWT

L'API utilise l'authentification JWT pour sécuriser les endpoints. Après connexion via `/api/auth/login`, le token JWT est retourné et doit être inclus dans l'en-tête `Authorization: Bearer {token}` pour accéder aux endpoints protégés.

## Structure du Projet

```
presso-api/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php
│   │           └── TransactionController.php
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── tests/
└──
```

## Module Transactions

Le module transactions permet de :

-   **Consulter** la liste des transactions existantes
-   **Créer** de nouvelles transactions

## Sécurité

-   Authentification JWT obligatoire pour accéder aux endpoints des transactions
-   Validation des données d'entrée

## Base de Données

La base de données PostgreSQL `presso_api` contient les tables nécessaires. Les migrations et seeders sont fournis pour l'initialisation automatique des données.
