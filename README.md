# Kitsune - Fiction interactive

Kitsune est une fiction interactive inspirée du folklore japonais, où vous incarnez un voyageur découvrant les légendes autour des esprits-renards (kitsune). Vos choix influenceront votre parcours et détermineront votre destin dans le Japon rural empli de mystères et de magie.

## Fonctionnalités principales
- [Système de choix : Chaque décision influence le déroulement de l'histoire]
- [Sauvegarde de progression : Reprenez votre aventure là où vous l'avez laissée]
- [Authentification : Créez un compte pour accéder à la page d'information "en savoir plus"]

## Installation

### Prérequis
- [PHP 8.1 ou supérieur]
- [Composer]
- [Node.js (v16 ou supérieur)]
- [npm]

### Étapes d'installation
1. Clonez le dépôt :
    ```bash
    git clone https://github.com/Dorytos13/kitsune.git
    cd kitsune
    ```
2. Installez les dépendances :
    1. PHP
    ```bash
    [composer install]
    ```
    2. JavaScript
    ```bash
    [npm install]
    ```
3. Copier le fichier d'environnement :
    - Créez un fichier `.env` à la racine du projet.
      ```
      cp .env.example .env
      ```
4. Générer la clé d'application :
    ```bash
    [php artisan key:generate]
    ```
5. Créer la base de donnée SQLite : 
    ```bash
    [touch database/database.sqlite]
    ```
6. Exécutez les migrations et seeders :
    ```bash
    [php artisan migrate --seed]
    ```
7. Lancer les machines :
     ```bash
    [composer run dev]
    ```

## Tester l'application
Accédez à l'application via [http://localhost:8000](http://localhost:8000)

## Stockage des images
Les images des chapitres sont stockées dans le dossier public. Pour les rendre accessibles publiquement :
```bash
    [php artisan storage:link]
   ```
## Structure du projet
### Backend (Laravel) 
1. Modèles :
    Story, Chapter, Choice, User, Progress

2. Contrôleurs :
    API controllers versionnés dans app/Http/Controllers/Api/V1

3. Migrations :
    Définition des tables dans /migrations

4. Routes API :
    Définies dans api.php avec préfixe /api/v1

5. Middleware : 
 Protection des routes nécessitant une authentification (/about)

6. FromRequest :
    Validation des données entrantes

### Frontend (Vue.js)

1. Composants :
Organisés par fonctionnalité dans le dossier /components

2. Vues :
Pages principales dans /views

3. Composables : 
logique dans /composables

4. Router :
Configuration de navigation dans /router

5. Utilitaires :
Fonctions communes dans /utils

## API RESTful
L'API est versionnées et préfixée avec /api/v1. Principales routes :

### Histoires :
[GET /api/v1/stories] - Liste toutes les histoires
[GET /api/v1/stories/{id}] - Obtenir les détails d'une histoire et ses chapitres
[POST /api/v1/stories] - Crée une nouvelle histoire (authentification requise)
[PUT /api/v1/stories/{id}] - Met à jour une histoire (authentification requise)
[DELETE /api/v1/stories/{id}] - Supprime une histoire (authentification requise)

### Chapitres :
[GET /api/v1/chapters/{id}] - Obtient les détails d'un chapitre et ses choix
[POST /api/v1/chapters] - Crée un nouveau chapitre (authentification requise)
[PUT /api/v1/chapters/{id}] - Met à jour un chapitre (authentification requise)
[DELETE /api/v1/chapters/{id}] - Supprime un chapitre (authentification requise)

## Test rapide
Pour tester rapidement les perties protégées par l'authentification avec des données de démonstrations voici ce qu'il faut faire :
1. Installer le projet
2. Utilisez les identifiants :
    Email: user@exemple.com
    Mot de passe: Password


Projet Développé par Doriane Rosset, M52-1 Bachelor en Ingenierie des médias dans le cadre d'un projet commun entre les cours de WebMobUI (vue.js) et DevProdMed (Laravel)