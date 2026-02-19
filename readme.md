# Vite Gourmand — Symfony (Local + Docker)
## ✅ Prérequis

### Option A — Lancer en **Docker** (recommandé)
- Docker Desktop (Windows/Mac) ou Docker Engine (Linux)
- Docker Compose (inclus dans Docker Desktop)

### Option B — Lancer en **Local**
- PHP **8.4+** *(important : le `composer.lock` actuel nécessite PHP 8.4 pour Doctrine/PHPUnit)*
- Composer 2
- MySQL 8 (ou MariaDB compatible)

---

## 📦 Installation — Docker (recommandée)

### 1) Cloner le projet
```bash
git clone <URL_DU_REPO>
cd vite_gourmand
```

### 2) Vérifier qu’il n’y a pas un `compose.yaml` vide
> Si Docker affiche `empty compose file`, c’est souvent parce qu’un fichier `compose.yaml` vide existe.
- Renomme-le ou supprime-le :

**Windows (PowerShell)**
```powershell
ren compose.yaml compose.yaml.bak
```

**Linux/Mac**
```bash
mv compose.yaml compose.yaml.bak
```

### 3) Démarrer les containers
```bash
docker compose up -d --build
```

### 4) Installer les dépendances PHP
```bash
docker compose exec app composer install
```

### 5) Configurer la base + migrations + fixtures
```bash
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

### 6) Accéder à l’application
- Application : http://localhost:8080
- phpMyAdmin : http://localhost:8081  
  - serveur : `db`  
  - user : `root`  
  - mdp : `root`

---

## 💻 Installation — Local (sans Docker)

### 1) Cloner le projet
```bash
git clone <URL_DU_REPO>
cd vite_gourmand
```

### 2) Créer le fichier `.env.local`
Créer un fichier `.env.local` à la racine :

```env
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=change_me

DATABASE_URL="mysql://root:root@127.0.0.1:3306/vite_gourmand?serverVersion=8.0&charset=utf8mb4"
```

> Adapte `root:root` / port / nom de base selon la config.

### 3) Installer les dépendances
```bash
composer install
```

### 4) Créer la base + migrations + fixtures
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

### 5) Lancer le serveur
**Option A — Symfony CLI**
```bash
symfony serve
```

**Option B — Serveur PHP**
```bash
php -S 127.0.0.1:8000 -t public
```

➡️ Ouvrir : http://127.0.0.1:8000

---

## 🔐 Comptes de test (fixtures)
Les fixtures génèrent des utilisateurs. Le mot de passe = `prefix@email + @123`.

Exemples :
- Admin : `admin@vitegourmand.fr` / `admin@123`
- Employé : `employe@vitegourmand.fr` / `employe@123`

---

## 🧰 Commandes utiles

### Docker
Voir l’état des containers :
```bash
docker compose ps
```

Logs :
```bash
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db
```

Entrer dans le container PHP :
```bash
docker compose exec app bash
```

Clear cache :
```bash
docker compose exec app php bin/console cache:clear
docker compose exec app php bin/console cache:clear --env=prod
```

Stop / Start :
```bash
docker compose stop
docker compose start
```

Éteindre (garde la DB) :
```bash
docker compose down
```

Reset total (⚠️ supprime la DB) :
```bash
docker compose down -v
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

### Local
Clear cache :
```bash
php bin/console cache:clear
```

---

## ⚠️ Problèmes fréquents & solutions

### 1) `Your lock file does not contain a compatible set of packages (PHP ^8.4)`
Ton `composer.lock` demande PHP 8.4.

✅ Solutions :
- **Recommandé :** utiliser PHP **8.4** (en local et dans Docker)
- Sinon, il faut **downgrade** Doctrine/PHPUnit pour PHP 8.2 et regénérer `composer.lock` (non recommandé pour l’école si tu veux “zéro galère”).

### 2) `empty compose file`
Docker utilise `compose.yaml` s’il existe. S’il est vide → erreur.
➡️ Renommer `compose.yaml` ou lancer avec :
```bash
docker compose -f docker-compose.yml up -d --build
```

### 3) `failed to connect to the docker API ... dockerDesktopLinuxEngine`
Docker Desktop n’est pas démarré (Windows).
➡️ Ouvrir Docker Desktop, puis retenter.

### 4) Erreurs migrations / FK dupliquées
Si tu veux repartir de zéro (base de dev uniquement) :
```bash
docker compose exec app php bin/console doctrine:database:drop --force --if-exists
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec app php bin/console doctrine:fixtures:load --no-interaction
```

---

## 📁 Ports par défaut (Docker)
- Nginx : `8080 -> 80`
- phpMyAdmin : `8081 -> 80`
- MySQL : `3307 -> 3306`

---

## ✅ Contribution (workflow conseillé)
```bash
git checkout -b feature/ma-feature
git add .
git commit -m "feat: description claire"
git push origin feature/ma-feature
```

---

## 📌 Notes
- Ne jamais commit `.env.local`
- Les migrations/fixtures sont faites pour une base de dev (reset possible)
- Le site public est accessible via `/menus` (selon tes routes)
