<?php
/**
 * Creates custom content types for El Hogar de Corazón.
 * Run with: drush php:script setup/01_content_types.php
 */

use Drupal\node\Entity\NodeType;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

// ─── Content Type: Niña / Adolescente ────────────────────────────────────────
if (!NodeType::load('nina')) {
  NodeType::create([
    'type' => 'nina',
    'name' => 'Niña / Adolescente',
    'description' => 'Expediente de cada niña o adolescente en el hogar.',
    'new_revision' => TRUE,
  ])->save();
  echo "✓ Tipo de contenido 'nina' creado.\n";
}

// ─── Content Type: Necesidad ──────────────────────────────────────────────────
if (!NodeType::load('necesidad')) {
  NodeType::create([
    'type' => 'necesidad',
    'name' => 'Necesidad',
    'description' => 'Necesidad específica de una niña (ropa, libros, medicina, etc.).',
    'new_revision' => FALSE,
  ])->save();
  echo "✓ Tipo de contenido 'necesidad' creado.\n";
}

// ─── Content Type: Donación / Compromiso ─────────────────────────────────────
if (!NodeType::load('donacion')) {
  NodeType::create([
    'type' => 'donacion',
    'name' => 'Donación',
    'description' => 'Registro de compromiso de donación de un benefactor.',
    'new_revision' => FALSE,
  ])->save();
  echo "✓ Tipo de contenido 'donacion' creado.\n";
}

// ─── Content Type: Página de contenido público ───────────────────────────────
// Drupal installs 'page' and 'article' by default with standard profile.
// We rename 'article' to 'noticia' for news/testimonials.
$article = NodeType::load('article');
if ($article) {
  $article->set('name', 'Noticia / Testimonio');
  $article->set('description', 'Noticias, historias y testimonios del hogar.');
  $article->save();
  echo "✓ Tipo 'article' renombrado a 'Noticia / Testimonio'.\n";
}

echo "\n✓ Tipos de contenido configurados.\n";
