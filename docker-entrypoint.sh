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

  echo "[entrypoint] Downloading demo images..."
  IMG_DIR="$WEB_ROOT/sites/default/files/hogar-images"
  mkdir -p "$IMG_DIR"
  BASE="https://images.unsplash.com"
  declare -A IMGS=(
    [hero.jpg]="photo-1488521787991-ed7bbaae773c?w=1400&h=550&fit=crop&q=80"
    [quienes-somos.jpg]="photo-1529390079861-591de354faf5?w=900&h=500&fit=crop&q=80"
    [educacion.jpg]="photo-1497633762265-9d179a990aa6?w=700&h=450&fit=crop&q=80"
    [salud.jpg]="photo-1559839734-2b71ea197ec2?w=700&h=450&fit=crop&q=80"
    [arte.jpg]="photo-1452860606245-08befc0ff44b?w=700&h=450&fit=crop&q=80"
    [vocacional.jpg]="photo-1523240795612-9a054b0db644?w=700&h=450&fit=crop&q=80"
    [testimonio-lucia.jpg]="photo-1445205170230-053b83016050?w=800&h=500&fit=crop&q=80"
    [donativo-uniformes.jpg]="photo-1532629345422-7515f3d16bb6?w=800&h=500&fit=crop&q=80"
    [taller-pintura.jpg]="photo-1560807707-8cc77767d783?w=800&h=500&fit=crop&q=80"
    [transparencia.jpg]="photo-1554224155-6726b3ff858f?w=900&h=400&fit=crop&q=80"
    [como-ayudar.jpg]="photo-1593113598332-cd288d649433?w=900&h=400&fit=crop&q=80"
    [contacto.jpg]="photo-1516321318423-f06f85e504b3?w=900&h=400&fit=crop&q=80"
  )
  for NAME in "${!IMGS[@]}"; do
    curl -sL "${BASE}/${IMGS[$NAME]}" -o "$IMG_DIR/$NAME" || true
  done
  chown -R www-data:www-data "$IMG_DIR"

  $DRUSH cr
  echo "[entrypoint] Installation complete."
fi

# ── Start Apache ──────────────────────────────────────────────────────────────
echo "[entrypoint] Starting Apache..."
exec apache2-foreground
