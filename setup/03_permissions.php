<?php
/**
 * Configures permissions for each role.
 * Run with: drush php:script setup/03_permissions.php
 */

use Drupal\user\Entity\Role;

// ── Benefactor ──────────────────────────────────────────────────────────────
$benefactor = Role::load('benefactor');
if ($benefactor) {
  $benefactor->grantPermission('access content');
  $benefactor->grantPermission('access user profiles');
  $benefactor->grantPermission('view own unpublished content');
  // Can view necesidades and create donaciones
  $benefactor->grantPermission('create donacion content');
  $benefactor->grantPermission('edit own donacion content');
  $benefactor->save();
  echo "✓ Permisos para 'benefactor' configurados.\n";
}

// ── Personal Interno ─────────────────────────────────────────────────────────
$personal = Role::load('personal_interno');
if ($personal) {
  $personal->grantPermission('access content');
  $personal->grantPermission('access administration pages');
  $personal->grantPermission('view the administration theme');
  // Niñas
  $personal->grantPermission('create nina content');
  $personal->grantPermission('edit any nina content');
  $personal->grantPermission('delete any nina content');
  // Necesidades
  $personal->grantPermission('create necesidad content');
  $personal->grantPermission('edit any necesidad content');
  $personal->grantPermission('delete any necesidad content');
  // Ver donaciones
  $personal->grantPermission('access content overview');
  $personal->grantPermission('view any unpublished content');
  // Administer users (limited)
  $personal->grantPermission('administer users');
  $personal->save();
  echo "✓ Permisos para 'personal_interno' configurados.\n";
}

// ── Director ─────────────────────────────────────────────────────────────────
$director = Role::load('director');
if ($director) {
  // Director gets everything personal_interno has, plus reports
  foreach (['access content', 'access administration pages', 'view the administration theme',
            'create nina content', 'edit any nina content', 'delete any nina content',
            'create necesidad content', 'edit any necesidad content', 'delete any necesidad content',
            'create donacion content', 'edit any donacion content', 'delete any donacion content',
            'access content overview', 'view any unpublished content',
            'administer users', 'administer roles', 'administer site configuration'] as $perm) {
    $director->grantPermission($perm);
  }
  $director->save();
  echo "✓ Permisos para 'director' configurados.\n";
}

// ── Authenticated (base) ─────────────────────────────────────────────────────
$auth = Role::load('authenticated');
if ($auth) {
  $auth->grantPermission('access content');
  $auth->save();
  echo "✓ Permisos base para 'authenticated' configurados.\n";
}

echo "\n✓ Permisos configurados.\n";
