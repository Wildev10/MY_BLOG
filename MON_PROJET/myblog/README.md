#  MyBlog - API Backend

Une API REST complète pour un blog moderne, construite avec Laravel 12.

##  Fonctionnalités

###  Authentification
- Inscription / Connexion avec validation robuste
- OAuth Google
- Tokens Sanctum
- Rate limiting pour protection anti-brute force

###  Articles (Posts)
- CRUD complet avec autorisation
- Mode brouillon / publié
- Upload d'images
- Calcul automatique du temps de lecture
- Compteur de vues (unique par utilisateur/IP)
- Pagination et recherche
- Posts populaires (avec cache)

###  Tags & Catégories
- Système de tags multiples par article (max 5)
- Catégories avec couleurs personnalisées
- Filtrage par tag ou catégorie

###  Interactions Sociales
- Likes avec toggle
- Bookmarks (favoris)
- Système de followers/following
- Feed personnalisé (posts des personnes suivies)

###  Commentaires
- CRUD avec modération
- Notifications automatiques

###  Notifications
- Like, commentaire, follow
- Marquer comme lu(es)
- Compteur de non-lues

###  Profils
- Profil complet (avatar, bio, location)
- Username unique pour URL publiques
- Statistiques (posts, followers, following)

###  Administration
- Dashboard statistiques
- Gestion utilisateurs (ban, rôles)
- Modération posts/commentaires

###  Sécurité
- Rate limiting par type d'action
- Policies pour autorisations
- Form Requests pour validation
- Protection CORS

##  Installation

```bash
# Cloner le repo
git clone <repo-url>
cd myblog

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_DATABASE=myblog
# ...

# Lancer les migrations
php artisan migrate

# Créer le lien storage
php artisan storage:link

# Seeder les données initiales (optionnel)
php artisan db:seed --class=TagSeeder

# Lancer le serveur
php artisan serve
```

##  Documentation API

Voir [API_DOCUMENTATION.md](API_DOCUMENTATION.md) pour la documentation complète.

##  Tests

```bash
# Lancer tous les tests
php artisan test

# Lancer les tests avec couverture
php artisan test --coverage
```

##  Structure du projet

```
app/
├── Http/
│   ├── Controllers/     # Logique métier
│   ├── Middleware/      # Rate limiting, auth
│   └── Requests/        # Validation des requêtes
├── Models/              # Modèles Eloquent
└── Policies/            # Autorisations
database/
├── factories/           # Factories pour tests
├── migrations/          # Structure BDD
└── seeders/            # Données initiales
tests/
├── Feature/            # Tests d'intégration
└── Unit/               # Tests unitaires
```

##  Technologies

- **Laravel 12** - Framework PHP
- **Laravel Sanctum** - Authentification API
- **MySQL** - Base de données
- **Laravel Socialite** - OAuth

##  Licence

MIT

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
