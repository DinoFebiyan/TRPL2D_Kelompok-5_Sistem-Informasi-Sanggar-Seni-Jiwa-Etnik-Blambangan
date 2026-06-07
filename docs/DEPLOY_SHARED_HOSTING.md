# =============================================================
#  Jiwa Etnik Blambangan — Shared Hosting Deployment Guide
#  For cPanel / DirectAdmin / Plesk (WITHOUT Docker)
# =============================================================

## Requirements

| Requirement | Minimum | Recommended |
|---|---|---|
| PHP | 8.2 | 8.3 |
| MySQL | 5.7 | 8.0 |
| Storage | 512 MB | 1 GB+ |
| PHP Extensions | pdo_mysql, gd, mbstring, openssl, zip, intl, fileinfo, bcmath, ctype | All listed |

---

## Step-by-Step Deployment

### 1. Build Assets Locally (on your dev machine)

Before uploading, build the frontend assets:

```bash
npm install
npm run build
```

This creates the `public/build/` folder with compiled CSS and JS.

---

### 2. Upload Files

**Option A — Upload via FTP/FileZilla:**

Upload the following directories and files to your hosting:
```
/app
/bootstrap
/config
/database
/public          ← including the /build folder you just created
/resources
/routes
/storage
/tests
/artisan
/composer.json
/composer.lock
/package.json
/tailwind.config.js
/vite.config.js
```

**Do NOT upload:**
- `/vendor` (will be installed on server)
- `/node_modules` (not needed on server)
- `/.env` (will be created on server)
- `/.git`

**Option B — Git deploy (recommended if hosting supports SSH):**

```bash
git clone https://github.com/your-repo/sistem_informasi_Jiwa_Etnik_Blambangan.git
cd sistem_informasi_Jiwa_Etnik_Blambangan
```

---

### 3. Install PHP Dependencies (via SSH)

```bash
composer install --no-dev --optimize-autoloader
```

If SSH is unavailable, some hosts allow running Composer from their control panel.

---

### 4. Set Document Root

In your hosting control panel (cPanel / DirectAdmin / Plesk), set the **document root** (web root) to the `public/` subdirectory:

```
/home/youraccount/public_html/public
```

> ⚠️ **Critical:** The document root MUST point to the `public/` folder, NOT the project root. If you cannot change the document root, see the workaround below.

**Workaround if document root cannot be changed:**
Copy the contents of `public/` to `public_html/` and update `public_html/index.php`:

```php
// Change these two lines:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// To point correctly to your project root, e.g.:
require __DIR__.'/../sistem_informasi_Jiwa_Etnik_Blambangan/vendor/autoload.php';
$app = require_once __DIR__.'/../sistem_informasi_Jiwa_Etnik_Blambangan/bootstrap/app.php';
```

---

### 5. Create .env File

Via SSH:
```bash
cp .env.example .env
nano .env   # or vim .env
```

Via cPanel File Manager: create a new file named `.env` and paste the contents of `.env.example`.

**Minimum required settings for shared hosting:**

```env
APP_NAME="Jiwa Etnik Blambangan"
APP_ENV=production
APP_KEY=           # Will be generated in next step
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync    # Use 'sync' if no cron job available

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_FROM_NAME="Jiwa Etnik Blambangan"

FILESYSTEM_DISK=public
```

---

### 6. Generate Application Key

```bash
php artisan key:generate
```

---

### 7. Run Database Migrations & Seed

```bash
# Run migrations
php artisan migrate --force

# Seed with default data (run only once on fresh install)
php artisan db:seed --force
```

---

### 8. Create Storage Symlink

```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public` symlink so uploaded images are accessible.

If symlinks are not supported on your hosting, you can configure direct storage in `config/filesystems.php` or ask your host to enable it.

---

### 9. Set File Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

### 10. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

### 11. Queue Worker (optional — for background jobs)

If your hosting supports cron jobs, add this to your crontab:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

For queue workers, add:
```
* * * * * cd /path/to/project && php artisan queue:work --max-time=55 >> /dev/null 2>&1
```

If cron jobs are unavailable, set `QUEUE_CONNECTION=sync` in `.env` (jobs run immediately, no background processing).

---

## Useful Artisan Commands

```bash
# Clear all caches (when deploying updates)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-cache after clearing
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Check application status
php artisan about

# Database management
php artisan migrate:status
php artisan migrate --force
```

---

## Updating an Existing Installation

When deploying a new version:

```bash
# 1. Pull latest code (if using git)
git pull origin main

# 2. Install new dependencies
composer install --no-dev --optimize-autoloader

# 3. Build new assets (on local machine, then upload public/build)
npm run build

# 4. Run any new migrations
php artisan migrate --force

# 5. Clear and re-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue worker (if applicable)
php artisan queue:restart
```

---

## Troubleshooting

| Problem | Solution |
|---|---|
| 500 Server Error | Check `storage/logs/laravel.log`, set `APP_DEBUG=true` temporarily |
| Images not showing | Run `php artisan storage:link` |
| White screen / no CSS | Ensure `public/build/` is uploaded |
| OTP email not sending | Verify SMTP credentials, check `MAIL_MAILER=smtp` |
| Session errors | Ensure `sessions` table exists (`php artisan migrate`) |
| "No application encryption key" | Run `php artisan key:generate` |
