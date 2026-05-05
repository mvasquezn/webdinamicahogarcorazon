<?php
/**
 * Creates role-based navigation menus and places them as sidebar blocks.
 * Run with: drush php:script setup/09_navigation.php
 */

use Drupal\system\Entity\Menu;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\block\Entity\Block;

// ── Helper ────────────────────────────────────────────────────────────────────
function ensure_menu(string $id, string $label): void {
  if (!Menu::load($id)) {
    Menu::create(['id' => $id, 'label' => $label])->save();
    echo "  + Menú '$label' creado.\n";
  }
}

function ensure_block(string $id, string $plugin, string $region, string $label, array $roles, int $weight = 0): void {
  if (!Block::load($id)) {
    Block::create([
      'id'      => $id,
      'plugin'  => $plugin,
      'theme'   => 'hogar_corazon',
      'region'  => $region,
      'weight'  => $weight,
      'status'  => TRUE,
      'settings' => [
        'id'             => $plugin,
        'label'          => $label,
        'label_display'  => 'visible',
        'level'          => 1,
        'depth'          => 0,
        'expand_all_items' => TRUE,
      ],
      'visibility' => [
        'user_role' => [
          'id'              => 'user_role',
          'roles'           => array_combine($roles, $roles),
          'negate'          => FALSE,
          'context_mapping' => ['user' => '@user.current_user_context:current_user'],
        ],
      ],
    ])->save();
    echo "  + Bloque '$label' → $region\n";
  }
}

// ═══════════════════════════════════════════════════════════════════
// MENÚ: Portal Benefactor
// ═══════════════════════════════════════════════════════════════════
echo "── Creando menú Portal Benefactor ──\n";

ensure_menu('portal-benefactor', 'Portal Benefactor');

$links_benefactor = [
  ['Mi panel',              'internal:/benefactor/panel',          0],
  ['Catálogo de necesidades','internal:/benefactor/necesidades',    1],
  ['Mis donaciones',        'internal:/benefactor/mis-donaciones', 2],
  ['Registrar compromiso',  'internal:/benefactor/comprometerse',  3],
];

foreach ($links_benefactor as [$title, $uri, $weight]) {
  $existing = \Drupal::entityTypeManager()
    ->getStorage('menu_link_content')
    ->loadByProperties(['title' => $title, 'menu_name' => 'portal-benefactor']);
  if (!$existing) {
    MenuLinkContent::create([
      'title'     => $title,
      'link'      => ['uri' => $uri],
      'menu_name' => 'portal-benefactor',
      'weight'    => $weight,
      'expanded'  => FALSE,
    ])->save();
    echo "  + Enlace '$title'\n";
  }
}

ensure_block(
  'portal_benefactor_nav',
  'system_menu_block:portal-benefactor',
  'sidebar_first',
  'Mi portal',
  ['benefactor'],
  -10
);

// ═══════════════════════════════════════════════════════════════════
// MENÚ: Panel Interno
// ═══════════════════════════════════════════════════════════════════
echo "── Creando menú Panel Interno ──\n";

ensure_menu('panel-interno', 'Panel Interno');

$links_interno = [
  ['Centro de reportes',       'internal:/interno/reportes',      0],
  ['Gestión de niñas',         'internal:/interno/ninas',         1],
  ['Gestión de necesidades',   'internal:/interno/necesidades',   2],
  ['Gestión de benefactores',  'internal:/interno/benefactores',  3],
];

foreach ($links_interno as [$title, $uri, $weight]) {
  $existing = \Drupal::entityTypeManager()
    ->getStorage('menu_link_content')
    ->loadByProperties(['title' => $title, 'menu_name' => 'panel-interno']);
  if (!$existing) {
    MenuLinkContent::create([
      'title'     => $title,
      'link'      => ['uri' => $uri],
      'menu_name' => 'panel-interno',
      'weight'    => $weight,
      'expanded'  => FALSE,
    ])->save();
    echo "  + Enlace '$title'\n";
  }
}

ensure_block(
  'panel_interno_nav',
  'system_menu_block:panel-interno',
  'sidebar_first',
  'Panel interno',
  ['personal_interno', 'director', 'administrator'],
  -10
);

\Drupal::service('cache.render')->invalidateAll();
echo "\n✓ Navegación por rol configurada.\n";
