# amallya_ul

Boutique seconde main en PHP avec:

- interface client
- panier avec quantite
- espace admin catalogue
- base MySQL simple

## Dossiers Principaux

- `frontend`
  logique visible dans `index.php`, `login.php`, `inscription.php`, `panier.php`, `mes_commandes.php`
- `backend`
  logique dans `config.php`, `app/config/`, `app/helpers/`, `traitement_*.php`, `commander.php`
- `database`
  scripts SQL dans `database/`
- `admin`
  dashboard et CRUD catalogue dans `admin/`

## Fichiers Importants

- `app/config/bootstrap.php`
  initialise session + PDO
- `app/config/database.php`
  configuration MySQL
- `app/helpers/catalog.php`
  helpers catalogue et images
- `app/helpers/orders.php`
  helpers commandes
- `database/schema.sql`
  structure base de donnees
- `database/seed_catalog.sql`
  donnees de demo
- `PROJECT_STRUCTURE.md`
  carte complete du projet

## Demarrage Rapide

1. Creer la base avec `database/schema.sql`
2. Inserer des produits avec `database/seed_catalog.sql`
3. Verifier `app/config/database.php`
4. Lancer le serveur local:

```powershell
cd c:\Users\AYA\Desktop\msql
php -S localhost:8000
```

5. Ouvrir:

- `http://localhost:8000/index.php`
- `http://localhost:8000/admin/gestion.php`
