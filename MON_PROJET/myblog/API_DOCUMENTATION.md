#  API Documentation - MyBlog

## Base URL
```
http://localhost:8000/api
```

## Authentication
Cette API utilise Laravel Sanctum pour l'authentification. Incluez le token dans le header `Authorization`:
```
Authorization: Bearer {votre_token}
```

---

## [AUTH] Authentification

### Inscription
```http
POST /register
```
| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| name | string | Oui | Nom de l'utilisateur (min: 2) |
| email | string | Oui | Email unique |
| password | string | Oui | Mot de passe (min: 8, 1 maj, 1 min, 1 chiffre) |
| password_confirmation | string | Oui | Confirmation du mot de passe |

**Rate Limit:** 5 requêtes/minute

### Connexion
```http
POST /login
```
| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| email | string | Oui | Email |
| password | string | Oui | Mot de passe |

**Rate Limit:** 5 requêtes/minute

### Déconnexion
```http
POST /logout
```
[Auth] **Authentification requise**

### Utilisateur courant
```http
GET /user
```
[Auth] **Authentification requise**

### Google OAuth
```http
GET /auth/google
GET /auth/google/callback
```

---

##  Posts

### Lister les posts
```http
GET /posts
```
| Query Param | Type | Description |
|-------------|------|-------------|
| page | int | Numéro de page (défaut: 1) |

### Posts populaires
```http
GET /posts/popular
```
Retourne les 10 posts les plus vus/likés (mis en cache 1h).

### Rechercher
```http
GET /posts/search?q={terme}
```
| Query Param | Type | Requis | Description |
|-------------|------|--------|-------------|
| q | string | Oui | Terme de recherche |

### Voir un post
```http
GET /posts/{id}
```
Enregistre automatiquement une vue (1 par IP/utilisateur par 24h).

### Créer un post
```http
POST /posts
```
[Auth] **Authentification requise**

| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| title | string | Oui | Titre (min: 5, max: 255) |
| content | string | Oui | Contenu (min: 50 caractères) |
| category_id | int | Non | ID de la catégorie |
| image | file | Non | Image (jpeg,png,jpg,gif,webp, max: 2Mo) |
| tags | array | Non | Liste de tags (max: 5) |
| status | string | Non | "draft" ou "published" (défaut: published) |

**Rate Limit:** 5 requêtes/heure

### Modifier un post
```http
PUT /posts/{id}
```
[Auth] **Authentification requise** (auteur uniquement)

### Supprimer un post
```http
DELETE /posts/{id}
```
[Auth] **Authentification requise** (auteur uniquement)

### Mes posts publiés
```http
GET /my-posts
```
[Auth] **Authentification requise**

### Mes brouillons
```http
GET /my-drafts
```
[Auth] **Authentification requise**

---

##  Likes

### Liker/Unliker un post
```http
POST /posts/{id}/like
```
[Auth] **Authentification requise**

**Rate Limit:** 30 requêtes/minute

### Voir les likes d'un post
```http
GET /posts/{id}/likes
```
[Auth] **Authentification requise**

---

##  Commentaires

### Lister les commentaires d'un post
```http
GET /posts/{id}/comments
```

### Créer un commentaire
```http
POST /posts/{id}/comments
```
[Auth] **Authentification requise**

| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| content | string | Oui | Contenu (min: 2, max: 1000) |

**Rate Limit:** 10 requêtes/minute

### Modifier un commentaire
```http
PUT /comments/{id}
```
[Auth] **Authentification requise** (auteur uniquement)

### Supprimer un commentaire
```http
DELETE /comments/{id}
```
[Auth] **Authentification requise** (auteur du commentaire, auteur du post, ou admin)

---

##  Bookmarks (Favoris)

### Lister mes bookmarks
```http
GET /bookmarks
```
[Auth] **Authentification requise**

### Toggle bookmark
```http
POST /posts/{id}/bookmark
```
[Auth] **Authentification requise**

### Statut bookmark
```http
GET /posts/{id}/bookmark/status
```
[Auth] **Authentification requise**

---

##  Follow (Abonnements)

### Suivre/Ne plus suivre
```http
POST /users/{username}/follow
```
[Auth] **Authentification requise**

### Statut follow
```http
GET /users/{username}/follow/status
```
[Auth] **Authentification requise**

### Liste des followers
```http
GET /users/{username}/followers
```

### Liste des following
```http
GET /users/{username}/following
```

### Feed (posts des personnes suivies)
```http
GET /feed
```
[Auth] **Authentification requise**

---

##  Tags

### Lister tous les tags
```http
GET /tags
```

### Tags populaires
```http
GET /tags/popular
```

### Posts d'un tag
```http
GET /tags/{slug}/posts
```

### Créer un tag (Admin)
```http
POST /tags
```
[Auth] **Authentification requise** (admin)

| Paramètre | Type | Requis | Description |
|-----------|------|--------|-------------|
| name | string | Oui | Nom du tag (unique, max: 50) |
| color | string | Non | Couleur hex (ex: #6366f1) |

### Supprimer un tag (Admin)
```http
DELETE /tags/{id}
```
[Auth] **Authentification requise** (admin)

---

##  Catégories

### Lister les catégories
```http
GET /categories
```

### Posts d'une catégorie
```http
GET /categories/{slug}/posts
```

### Créer une catégorie
```http
POST /categories
```
[Auth] **Authentification requise**

---

##  Profil

### Mon profil
```http
GET /profile
```
[Auth] **Authentification requise**

### Modifier mon profil
```http
PUT /profile
```
[Auth] **Authentification requise**

| Paramètre | Type | Description |
|-----------|------|-------------|
| name | string | Nom |
| username | string | Username unique |
| bio | string | Biographie (max: 500) |
| location | string | Localisation |

### Changer mot de passe
```http
PUT /profile/password
```
[Auth] **Authentification requise**

### Upload avatar
```http
POST /profile/avatar
```
[Auth] **Authentification requise**

### Supprimer avatar
```http
DELETE /profile/avatar
```
[Auth] **Authentification requise**

### Supprimer mon compte
```http
DELETE /profile
```
[Auth] **Authentification requise**

### Profil public
```http
GET /users/{username}
```

### Posts d'un utilisateur
```http
GET /users/{username}/posts
```

---

##  Notifications

### Lister mes notifications
```http
GET /notifications
```
[Auth] **Authentification requise**

### Nombre de non-lues
```http
GET /notifications/unread-count
```
[Auth] **Authentification requise**

### Marquer comme lue
```http
POST /notifications/{id}/mark-as-read
```
[Auth] **Authentification requise**

### Marquer toutes comme lues
```http
POST /notifications/mark-all-as-read
```
[Auth] **Authentification requise**

### Supprimer une notification
```http
DELETE /notifications/{id}
```
[Auth] **Authentification requise**

---

## [ADMIN] Admin

Toutes les routes admin nécessitent le rôle `admin`.

### Statistiques
```http
GET /admin/stats
```

### Gestion utilisateurs
```http
GET /admin/users
PUT /admin/users/{id}/toggle-admin
PUT /admin/users/{id}/toggle-ban
```

### Gestion posts
```http
GET /admin/posts
DELETE /admin/posts/{id}
```

### Gestion commentaires
```http
GET /admin/comments
DELETE /admin/comments/{id}
```

### Gestion catégories
```http
GET /admin/categories/stats
PUT /admin/categories/{id}
DELETE /admin/categories/{id}
```

---

##  Codes de réponse

| Code | Signification |
|------|---------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Non autorisé |
| 404 | Non trouvé |
| 422 | Erreur de validation |
| 429 | Trop de requêtes (rate limit) |
| 500 | Erreur serveur |

---

##  Format de réponse

### Succès
```json
{
    "success": true,
    "message": "Message de succès",
    "data": { ... }
}
```

### Erreur
```json
{
    "success": false,
    "message": "Message d'erreur",
    "errors": { ... }
}
```

### Pagination
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [...],
        "first_page_url": "...",
        "from": 1,
        "last_page": 5,
        "last_page_url": "...",
        "next_page_url": "...",
        "path": "...",
        "per_page": 10,
        "prev_page_url": null,
        "to": 10,
        "total": 50
    }
}
```

---

##  Rate Limiting Headers

Chaque réponse inclut des headers de rate limiting :
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
```

En cas de dépassement (429) :
```json
{
    "success": false,
    "message": "Trop de requêtes. Veuillez réessayer dans X secondes.",
    "retry_after": 60
}
```
