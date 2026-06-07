#!/bin/bash
# =============================================================
#  JEB Application Container Entrypoint
#  Runs on every container start (app + queue workers).
#  Controls: DB wait → migrate → seed → symlink → cache
# =============================================================
set -e

echo ""
echo "══════════════════════════════════════════════════"
echo "  Jiwa Etnik Blambangan — Container Boot"
echo "  Environment : ${APP_ENV:-unknown}"
echo "  Role        : ${CONTAINER_ROLE:-app}"
echo "══════════════════════════════════════════════════"

# ─────────────────────────────────────────────────────
# STEP 1: Install Composer dependencies if missing
#         (only needed in dev with volume mounts)
# ─────────────────────────────────────────────────────
if [ ! -f "vendor/autoload.php" ]; then
    echo "[1/5] vendor/ not found — running composer install..."
    composer install \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader
else
    echo "[1/5] vendor/ found. Skipping composer install."
fi

# ─────────────────────────────────────────────────────
# STEP 2: Wait for MySQL to be ready
# ─────────────────────────────────────────────────────
echo "[2/5] Waiting for database (${DB_HOST:-db}:${DB_PORT:-3306})..."
MAX_TRIES=40
TRIES=0

until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST:-db};port=${DB_PORT:-3306};dbname=${DB_DATABASE}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}'
        );
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ "$TRIES" -ge "$MAX_TRIES" ]; then
        echo "  ERROR: Database not reachable after ${MAX_TRIES} attempts. Aborting."
        exit 1
    fi
    echo "  Attempt ${TRIES}/${MAX_TRIES}: not ready yet — retrying in 2s..."
    sleep 2
done
echo "  Database is ready!"

# ─────────────────────────────────────────────────────
# STEP 3: Migrations (skip for queue worker role)
# ─────────────────────────────────────────────────────
if [ "${CONTAINER_ROLE}" != "queue" ]; then
    echo "[3/5] Running database migrations..."
    php artisan migrate --force

    # Auto-seed only in local/dev environment
    if [ "${APP_ENV}" = "local" ]; then
        echo "[3b]  Seeding database (local only)..."
        php artisan db:seed --force 2>/dev/null \
            && echo "       Seeding complete." \
            || echo "       Seeding skipped (data may already exist)."
    fi
else
    echo "[3/5] Queue worker role — skipping migrations."
fi

# ─────────────────────────────────────────────────────
# STEP 4: Storage symlink (idempotent)
# ─────────────────────────────────────────────────────
if [ "${CONTAINER_ROLE}" != "queue" ]; then
    echo "[4/5] Creating storage symlink..."
    php artisan storage:link 2>/dev/null \
        && echo "       Symlink created." \
        || echo "       Symlink already exists — skipping."
else
    echo "[4/5] Queue worker role — skipping storage:link."
fi

# ─────────────────────────────────────────────────────
# STEP 5: Cache optimization
# ─────────────────────────────────────────────────────
if [ "${APP_ENV}" = "local" ]; then
    echo "[5/5] Clearing caches (local dev)..."
    php artisan config:clear  2>/dev/null || true
    php artisan route:clear   2>/dev/null || true
    php artisan view:clear    2>/dev/null || true
    php artisan event:clear   2>/dev/null || true
else
    echo "[5/5] Caching config, routes, views (${APP_ENV})..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

echo ""
echo "  Application ready! Handing off to: $@"
echo "══════════════════════════════════════════════════"
echo ""

exec "$@"
