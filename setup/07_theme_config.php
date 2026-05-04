<?php
/**
 * Configures theme, front page, site variables and admin theme.
 * Run with: drush php:script setup/07_theme_config.php
 */

$config = \Drupal::configFactory();

// ── Front page ────────────────────────────────────────────────────────────────
$config->getEditable('system.site')
  ->set('name', 'El Hogar de Corazón')
  ->set('slogan', 'Protegemos, educamos y amamos')
  ->set('mail', 'admin@hogarcorazon.org')
  ->set('page.front', '/inicio')
  ->set('page.403', '/user/login')
  ->set('page.404', '/inicio')
  ->save();
echo "✓ Configuración del sitio guardada.\n";

// ── Install custom theme + admin theme ───────────────────────────────────────
\Drupal::service('theme_installer')->install(['hogar_corazon', 'claro']);

$config->getEditable('system.theme')
  ->set('default', 'hogar_corazon')
  ->set('admin', 'claro')
  ->save();

echo "✓ Tema frontend: hogar_corazon · Tema admin: claro\n";

// ── Pathauto patterns ─────────────────────────────────────────────────────────
// Requires pathauto module (already enabled)
if (\Drupal::moduleHandler()->moduleExists('pathauto')) {
  \Drupal::entityTypeManager()
    ->getStorage('pathauto_pattern')
    ->create([
      'id'        => 'nina_pattern',
      'label'     => 'Patrón niñas',
      'type'      => 'canonical_entities:node',
      'pattern'   => 'expediente/[node:field_expediente_num]',
      'selection_criteria' => [
        'entity_bundle:node' => [
          'id'      => 'entity_bundle:node',
          'bundles' => ['nina' => 'nina'],
          'negate'  => FALSE,
          'context_mapping' => ['node' => 'node'],
        ],
      ],
    ])->save();
  echo "✓ Patrón pathauto para niñas creado.\n";
}

// ── Contact form (general) ────────────────────────────────────────────────────
if (\Drupal::moduleHandler()->moduleExists('contact')) {
  if (!\Drupal::entityTypeManager()->getStorage('contact_form')->load('contacto_general')) {
    \Drupal::entityTypeManager()->getStorage('contact_form')->create([
      'id'          => 'contacto_general',
      'label'       => 'Contacto General',
      'recipients'  => ['contacto@hogarcorazon.org'],
      'reply'       => 'Gracias por contactarnos. Responderemos en breve.',
      'weight'      => 0,
    ])->save();
    echo "✓ Formulario de contacto creado.\n";
  }
}

// ── User registration (benefactor flow) ──────────────────────────────────────
$config->getEditable('user.settings')
  ->set('register', 'visitors')        // allow visitor registration
  ->set('verify_mail', TRUE)
  ->set('notify.register_pending_approval', TRUE)
  ->set('notify.register_no_approval_required', TRUE)
  ->save();
echo "✓ Registro de usuarios configurado (requiere aprobación de admin).\n";

// ── Date format ───────────────────────────────────────────────────────────────
$config->getEditable('core.date_format.medium')
  ->set('pattern', 'd \d\e F \d\e Y')
  ->save();

echo "\n✓ Configuración de tema y sitio completada.\n";
