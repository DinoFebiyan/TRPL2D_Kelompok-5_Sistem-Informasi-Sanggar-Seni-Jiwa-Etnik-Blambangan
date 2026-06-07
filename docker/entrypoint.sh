#!/bin/bash
# =============================================================
#  JEB Application Container Entrypoint
#  Runs on every container start (app + queue workers).
#  Controls: validate → DB wait → migrate → symlink → cache
# =============================================================
set -e

_DB_HOST="${DB_HOST:-db}"
_DB_PORT="${DB_PORT:-3306}"
_DB_USER="${DB_USERNAME:-root}"
_DB_PASS="${DB_PASSWORD:-}"
_DB_NAME="${DB_DATABASE:-jeb_db}"
_ROLE="${CONTAINER_ROLE:-app}"
_ENV="${APP_ENV:-local}"

echo ""
echo "══════════════════════════════════════════════════"
echo "  Jiwa Etnik Blambangan — Container Boot"
echo "  Environment : ${_ENV}"
echo "  Role        : ${_ROLE}"
echo "══════════════════════════════════════════════════"

# ─────────────────────────────────────────────────────
# STEP 0: Pre-flight checks
# ─────────────────────────────────────────────────────
echo "[0/5] Running pre-flight checks..."

# 0a. Abort if APP_KEY is missing (prevents MissingAppKeyException at runtime)
if [ -z "${APP_KEY}" ]; then
    echo ""
    echo "  ✖ FATAL: APP_KEY is not set!"
    echo "    Generate one with: php artisan key:generate --show"
    echo "    Then add it to your .env file on the host and restart."
    echo ""
    exit 1
fi
echo "  ✔ APP_KEY is set."

# 0b. Ensure storage subdirectories exist with correct permissions
#     (bind-mounted volume may arrive as an empty directory)
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

chmod -R 775 storage bootstrap/cache 2>/dev/null || true
echo "  ✔ Storage directories verified."

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
#         Uses mysqladmin ping — safer than PHP PDO
#         because credentials are not embedded in code.
# ─────────────────────────────────────────────────────
echo "[2/5] Waiting for database (${_DB_HOST}:${_DB_PORT})..."
MAX_TRIES=40
TRIES=0

until mysqladmin ping \
    -h "${_DB_HOST}" \
    -P "${_DB_PORT}" \
    -u "${_DB_USER}" \
    -p"${_DB_PASS}" \
    --silent 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ "$TRIES" -ge "$MAX_TRIES" ]; then
        echo "  ✖ ERROR: Database not reachable after ${MAX_TRIES} attempts. Aborting."
        exit 1
    fi
    echo "  Attempt ${TRIES}/${MAX_TRIES}: not ready yet — retrying in 3s..."
    sleep 3
done
echo "  ✔ Database is ready!"

# ─────────────────────────────────────────────────────
# STEP 3: Migrations (skip for queue worker role)
# ─────────────────────────────────────────────────────
if [ "${_ROLE}" != "queue" ]; then
    echo "[3/5] Running database migrations..."
    php artisan migrate --force

    # Auto-seed only in local/dev environment
    if [ "${_ENV}" = "local" ]; then
        echo "[3b]  Seeding database (local only)..."
        php artisan db:seed --force 2>/dev/null \
            && echo "       ✔ Seeding complete." \
            || echo "       ⚠ Seeding skipped (data may already exist)."
    fi
else
    echo "[3/5] Queue worker role — skipping migrations."
fi

# ─────────────────────────────────────────────────────
# STEP 4: Storage symlink (idempotent — safe to re-run)
#         Must run BEFORE config:cache so cached routes
#         and views can reference storage:: URLs correctly.
# ─────────────────────────────────────────────────────
if [ "${_ROLE}" != "queue" ]; then
    echo "[4/5] Creating storage symlink..."
    php artisan storage:link 2>/dev/null \
        && echo "       ✔ Symlink created." \
        || echo "       ℹ Symlink already exists — skipping."
else
    echo "[4/5] Queue worker role — skipping storage:link."
fi

# ─────────────────────────────────────────────────────
# STEP 5: Cache optimization
#         Queue workers skip caching — they share the
#         same cached config written by the app container.
# ─────────────────────────────────────────────────────
if [ "${_ROLE}" != "queue" ]; then
    if [ "${_ENV}" = "local" ]; then
        echo "[5/5] Clearing caches (local dev)..."
        php artisan config:clear 2>/dev/null || true
        php artisan route:clear  2>/dev/null || true
        php artisan view:clear   2>/dev/null || true
        php artisan event:clear  2>/dev/null || true
    else
        echo "[5/5] Caching config, routes, views (${_ENV})..."
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan event:cache
    fi
else
    echo "[5/5] Queue worker role — skipping cache build."
fi

echo ""
echo "  ✔ Application ready! Handing off to: $*"
echo "══════════════════════════════════════════════════"
echo ""

exec "$@"
