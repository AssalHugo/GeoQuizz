# GeoQuizz

## 📋 Prérequis

- Docker et Docker Compose
- Node.js et npm
- PHP 8.3+
- Composer

## ⚙️ Configuration du projet

### 1. Structure des fichiers

```tree
GeoQuizz/
├── Backend/
│   ├── app-auth/
│   ├── app-gateway/
│   ├── app-geoquizz/
│   │   └── geoquizz.db.ini
│   └── tokenDirectus.ini
├── Frontend/
├── geoquizz.env
├── geoquizzdb.env
└── geodirectus.env
```

### 2. Installation des dépendances

#### Backend
```bash
cd Backend/app-auth && composer install
cd ../app-geoquizz && composer install
cd ../app-gateway && composer install
```

#### Frontend
```bash
cd Frontend && npm install
```

### 3. Base de données

1. Lancer les conteneurs Docker :
```bash
docker compose up -d
```

2. Attendre que les services soient démarrés (vérifier avec ```docker compose ps```).

3. Initialiser la base de données PostgreSQL via Adminer :

    - Accéder à Adminer : http://localhost:32501
    - Se connecter avec les informations suivantes :
        ```
        Driver : PostgreSQL
        Host : geoquizz.db
        User : root
        Password : root
        ```

4. Créer les BD si elles n'y sont pas déjà: 
    - ```auth.db```
    - ```geoquizz.db```
    - ```directus.db```

5. Utiliser les scripts SQL pour créer les tables et insérer les données :

    1. Dans Adminer, aller dans "Importer"
    2. Sélectionner les fichiers SQL suivants et les importer un par un :
        ```GEOQUIZZ_seriesDirectus.sql```
        ```GEOQUIZZ_dataDirectus.sql```
        ```GEOQUIZZ_Auth.sql```
        ```GEOQUIZZ_servicesGeoquizz.sql```

### 4. Configuration de Directus

1. Accéder à l'interface Directus : http://localhost:32504
2. Se connecter avec les credentials définis dans ```geodirectus.env```.
3. Créer les collections "photos" et "series" selon le schéma fourni dans les scripts SQL.

### 5. Lancement du projet
Démarrer tous les services :
```bash
docker compose up -d
```
Lancer le frontend :
```bash
cd Frontend
npm run dev
```

On peut se connecter avec les utilisateurs suivants :
| Email | Password |
|-------|----------|
| anne.willow@outlook.com | willy72 |
| mariko@gmail.com | corona493 |
| tate@gmail.com | password1999 |


### 6. Ports utilisés
Frontend : http://localhost:5173
Gateway : http://localhost:32500
Adminer : http://localhost:32501
API GeoQuizz : http://localhost:32502
API Auth : http://localhost:32503
API Directus : http://localhost:32504

### 7. Structure des services
Gateway (app-gateway) : Point d'entrée de l'API
Auth (app-auth) : Gestion de l'authentification
GeoQuizz (app-geoquizz) : Logique métier du jeu
Directus (app-directus) : Gestion des médias et séries