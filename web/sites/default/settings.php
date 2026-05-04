<?php

/**
 * Settings for El Hogar de Corazón — Drupal 11
 *
 * Reads all sensitive values from environment variables so the file
 * is safe to commit. A settings.local.php (git-ignored) can override
 * any value for local development.
 */

// ── Database ──────────────────────────────────────────────────────────────────
// Railway sets MYSQL_URL (or individual vars). We support both.
if ($db_url = getenv('MYSQL_URL') ?: getenv('DATABASE_URL')) {
  $info = parse_url($db_url);
  $databases['default']['default'] = [
    'driver'     => 'mysql',
    'namespace'  => 'Drupal\\mysql\\Driver\\Database\\mysql',
    'autoload'   => 'core/modules/mysql/src/Driver/Database/mysql/',
    'database'   => ltrim($info['path'], '/'),
    'username'   => $info['user'],
    'password'   => $info['pass'] ?? '',
    'host'       => $info['host'],
    'port'       => $info['port'] ?? '3306',
    'prefix'     => '',
    'isolation_level' => 'READ COMMITTED',
  ];
} else {
  $databases['default']['default'] = [
    'driver'     => 'mysql',
    'namespace'  => 'Drupal\\mysql\\Driver\\Database\\mysql',
    'autoload'   => 'core/modules/mysql/src/Driver/Database/mysql/',
    'database'   => getenv('DB_NAME')     ?: 'drupal',
    'username'   => getenv('DB_USER')     ?: 'drupal',
    'password'   => getenv('DB_PASSWORD') ?: 'drupal',
    'host'       => getenv('DB_HOST')     ?: 'db',
    'port'       => getenv('DB_PORT')     ?: '3306',
    'prefix'     => '',
    'isolation_level' => 'READ COMMITTED',
  ];
}

// ── Security ──────────────────────────────────────────────────────────────────
$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT')
  ?: 'pQoKDIqukq0-KIQ9STmJEKINRVhkMr0zdwbIMBnYADOeJ7HlrspzAz-oyvdjMHpYVyngRishJA';

$settings['update_free_access'] = FALSE;

// ── Trusted host patterns ─────────────────────────────────────────────────────
// Add your Railway domain here, or set DRUPAL_TRUSTED_HOST env var.
$trusted = getenv('DRUPAL_TRUSTED_HOST');
if ($trusted) {
  $settings['trusted_host_patterns'] = array_map('trim', explode(',', $trusted));
} else {
  $settings['trusted_host_patterns'] = [
    '^localhost$',
    '^\d+\.\d+\.\d+\.\d+$',          // any IP (dev)
    '^.*\.up\.railway\.app$',          // Railway preview URLs
    '^.*\.railway\.app$',
  ];
}

// ── Config sync directory ─────────────────────────────────────────────────────
$settings['config_sync_directory'] = '../config/sync';

// ── File system ───────────────────────────────────────────────────────────────
$settings['file_public_path']  = 'sites/default/files';
$settings['file_private_path'] = getenv('DRUPAL_PRIVATE_FILES') ?: '/var/drupal_private';
$settings['file_temp_path']    = '/tmp';

// ── Performance (production) ──────────────────────────────────────────────────
if (getenv('APP_ENV') === 'production') {
  $config['system.performance']['css']['preprocess']  = TRUE;
  $config['system.performance']['js']['preprocess']   = TRUE;
  $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
}

// ── Local overrides (git-ignored) ─────────────────────────────────────────────
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
