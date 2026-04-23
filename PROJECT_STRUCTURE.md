# Project Structure

## Frontend

Le frontend est la partie visible pour le client.

- `index.php`
  page d'accueil
- `categories.php`
  page categories
- `produits.php`
  page catalogue produits avec React
- `api/products.php`
  endpoint JSON pour alimenter le catalogue React
- `js/catalog-app.js`
  application React du catalogue, recherche, tri, filtre et affichage
- `login.php`
  page connexion
- `inscription.php`
  page inscription
- `panier.php`
  page panier avec quantite
- `mes_commandes.php`
  historique des commandes client
- `css/style.css`
  design, layout, animations
- `js/script.js`
  recherche, tri, affichage liste/grille, reveal animation
- `images/`
  logo et images produits

## Backend

Le backend contient la logique PHP et la connexion base de donnees.

- `config.php`
  point d'entree commun pour charger le bootstrap
- `app/config/bootstrap.php`
  demarre la session et cree la connexion PDO
- `app/config/database.php`
  identifiants MySQL
- `app/helpers/catalog.php`
  helpers produits, images et cartes catalogue
- `app/helpers/orders.php`
  helpers commandes
- `app/helpers/auth.php`
  protection des pages connectees
- `app/helpers/ui.php`
  layout commun frontend/admin/auth
- `traitement_login.php`
  verification utilisateur
- `traitement_inscription.php`
  creation compte
- `ajouter_panier.php`
  augmente la quantite d'un produit
- `supprimer_panier.php`
  diminue, augmente ou supprime un produit du panier
- `commander.php`
  enregistre la commande
- `logout.php`
  destruction session

## Admin

Le back office de gestion catalogue.

- `admin/gestion.php`
  dashboard admin
- `admin/ajouter.php`
  ajout produit
- `admin/modifier.php`
  modification produit
- `admin/supprimer.php`
  suppression produit

## Database

La base de donnees contient les tables et les donnees de demo.

- `database/schema.sql`
  creation des tables `users`, `produits`, `commandes`
- `database/seed_catalog.sql`
  insertion du catalogue demo
- `seed_catalog.php`
  alternative PHP pour remplir la table produits

## Data Flow

1. le client ouvre `index.php`, `categories.php` ou `produits.php`
2. `config.php` charge `app/config/bootstrap.php`
3. `bootstrap.php` cree `$pdo`
4. les pages utilisent les helpers dans `app/helpers/`
5. `produits.php` envoie les donnees initiales au frontend React
6. les formulaires et actions enregistrent les donnees en base MySQL
