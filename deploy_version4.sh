#!/usr/bin/env bash
set -euo pipefail

# Deploy script for branch version4 on Linux servers.
# It refreshes dependencies and clears caches to avoid missing Filament components.

APP_DIR="/var/www/gestion_documentos_obras"
BRANCH="version4"
PRESERVE_DATABASE_CONFIG="${PRESERVE_DATABASE_CONFIG:-0}"
DB_CONFIG_RELATIVE_PATH="config/database.php"
DB_CONFIG_BACKUP=""

if [[ "${1:-}" != "" ]]; then
  APP_DIR="$1"
fi

echo "[1/11] Entering project directory: ${APP_DIR}"
cd "${APP_DIR}"

# Ensure old local git flags do not block config updates from the branch.
git update-index --no-skip-worktree "${DB_CONFIG_RELATIVE_PATH}" || true

echo "[2/11] Enabling maintenance mode"
php artisan down || true

if [[ "${PRESERVE_DATABASE_CONFIG}" == "1" && -f "${DB_CONFIG_RELATIVE_PATH}" ]]; then
  echo "Backing up ${DB_CONFIG_RELATIVE_PATH} before git pull"
  DB_CONFIG_BACKUP="$(mktemp)"
  cp "${DB_CONFIG_RELATIVE_PATH}" "${DB_CONFIG_BACKUP}"
fi

echo "[3/11] Fetching and updating branch ${BRANCH}"
git fetch origin
git checkout "${BRANCH}"
git pull origin "${BRANCH}"

if [[ "${PRESERVE_DATABASE_CONFIG}" != "1" ]]; then
  echo "Syncing ${DB_CONFIG_RELATIVE_PATH} from branch ${BRANCH}"
  git checkout "origin/${BRANCH}" -- "${DB_CONFIG_RELATIVE_PATH}"
fi

if [[ -n "${DB_CONFIG_BACKUP}" ]]; then
  echo "Restoring preserved ${DB_CONFIG_RELATIVE_PATH}"
  cp "${DB_CONFIG_BACKUP}" "${DB_CONFIG_RELATIVE_PATH}"
  rm -f "${DB_CONFIG_BACKUP}"
fi

echo "[4/11] Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "[5/11] Rebuilding package discovery"
php artisan package:discover --ansi

echo "[6/11] Clearing caches"
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/services.php || true

echo "[7/11] Verifying Filament Tables package"
if ! composer show filament/tables > /dev/null 2>&1; then
  echo "filament/tables is missing. Installing Filament 4 packages..."
  composer require filament/filament:^4.0 filament/forms:^4.0 filament/tables:^4.0 --no-interaction
  php artisan package:discover --ansi
  php artisan optimize:clear
fi

echo "[8/11] Running migrations"
php artisan migrate --force

echo "[9/11] Rebuilding production caches"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[10/11] Restarting queue workers"
php artisan queue:restart || true

echo "[11/12] Restarting web runtime (php-fpm/apache) when available"
if command -v systemctl > /dev/null 2>&1; then
  systemctl restart php8.3-fpm || true
  systemctl restart php8.2-fpm || true
  systemctl restart php8.1-fpm || true
  systemctl restart apache2 || true
  systemctl restart httpd || true
fi

echo "[12/12] Disabling maintenance mode"
php artisan up || true

echo "Deployment finished successfully."
