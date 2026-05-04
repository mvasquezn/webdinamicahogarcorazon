#!/bin/bash
set -e

DRUSH="/var/www/html/vendor/bin/drush"
WEB_ROOT="/var/www/html/web"

# ── Wait for MySQL ────────────────────────────────────────────────────────────
DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"

echo "[entrypoint] Waiting for MySQL at $DB_HOST:$DB_PORT..."
until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"${DB_USER:-drupal}" -p"${DB_PASSWORD:-drupal}" --silent 2>/dev/null; do
  sleep 2
done
echo "[entrypoint] MySQL ready."

# ── Fix permissions on writable directories ───────────────────────────────────
mkdir -p "$WEB_ROOT/sites/default/files"
chown -R www-data:www-data "$WEB_ROOT/sites/default/files" /var/drupal_private 2>/dev/null || true
chmod -R 755 "$WEB_ROOT/sites/default/files"

# ── Config sync directory ─────────────────────────────────────────────────────
mkdir -p /var/www/html/config/sync

# ── Install or update Drupal ──────────────────────────────────────────────────
cd /var/www/html

if $DRUSH status --field=bootstrap 2>/dev/null | grep -q "Successful"; then
  echo "[entrypoint] Drupal already installed — running updates..."
  $DRUSH updb -y
  $DRUSH cr
else
  echo "[entrypoint] First deploy — installing Drupal..."

  DB_URL="mysql://${DB_USER:-drupal}:${DB_PASSWORD:-drupal}@${DB_HOST:-db}:${DB_PORT:-3306}/${DB_NAME:-drupal}"

  $DRUSH site:install standard \
    --db-url="$DB_URL" \
    --site-name="${DRUPAL_SITE_NAME:-El Hogar de Corazón}" \
    --site-mail="${DRUPAL_SITE_MAIL:-admin@hogarcorazon.org}" \
    --account-name="${DRUPAL_ADMIN_USER:-admin}" \
    --account-pass="${DRUPAL_ADMIN_PASS:-Admin2024!}" \
    --account-mail="${DRUPAL_SITE_MAIL:-admin@hogarcorazon.org}" \
    --locale=es \
    -y

  echo "[entrypoint] Enabling modules..."
  $DRUSH en admin_toolbar admin_toolbar_tools pathauto token ctools redirect \
    metatag views_bulk_operations contact telephone -y

  echo "[entrypoint] Running setup scripts..."
  for script in /var/www/html/setup/0*.php; do
    echo "[entrypoint]  → $script"
    $DRUSH php:script "$script"
  done

  $DRUSH cr
  echo "[entrypoint] Installation complete."
fi

# ── Start Apache ──────────────────────────────────────────────────────────────
echo "[entrypoint] Starting Apache..."
exec apache2-foreground
