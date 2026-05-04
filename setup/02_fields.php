<?php
/**
 * Creates fields for all custom content types.
 * Run with: drush php:script setup/02_fields.php
 */

use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Entity\Entity\EntityFormDisplay;
use Drupal\Core\Entity\Entity\EntityViewDisplay;

/**
 * Helper: creates a field storage + instance if not already present.
 */
function ensure_field(string $entity, string $bundle, string $field_name, string $field_type, string $label, array $storage_settings = [], array $field_settings = [], bool $required = FALSE): void {
  if (!FieldStorageConfig::loadByName($entity, $field_name)) {
    FieldStorageConfig::create([
      'field_name'  => $field_name,
      'entity_type' => $entity,
      'type'        => $field_type,
      'settings'    => $storage_settings,
    ])->save();
  }
  if (!FieldConfig::loadByName($entity, $bundle, $field_name)) {
    FieldConfig::create([
      'field_name'  => $field_name,
      'entity_type' => $entity,
      'bundle'      => $bundle,
      'label'       => $label,
      'required'    => $required,
      'settings'    => $field_settings,
    ])->save();
    echo "  + campo '$field_name' en '$bundle'\n";
  }
}

// ═══════════════════════════════════════════════════════════════════
// CAMPOS: niña
// ═══════════════════════════════════════════════════════════════════
echo "── Campos para 'nina' ──\n";

ensure_field('node', 'nina', 'field_fecha_nacimiento',  'datetime',  'Fecha de nacimiento',  ['datetime_type' => 'date']);
ensure_field('node', 'nina', 'field_fecha_ingreso',     'datetime',  'Fecha de ingreso',     ['datetime_type' => 'date'], [], TRUE);
ensure_field('node', 'nina', 'field_fecha_salida',      'datetime',  'Fecha de salida',      ['datetime_type' => 'date']);
ensure_field('node', 'nina', 'field_tipo_egreso',       'list_string', 'Tipo de egreso', [],
  ['allowed_values' => [
    'reintegracion' => 'Reintegración familiar',
    'adopcion'      => 'Adopción',
    'mayoria_edad'  => 'Mayoría de edad',
    'traslado'      => 'Traslado',
    'fuga'          => 'Fuga',
    'defuncion'     => 'Defunción',
  ]]);
ensure_field('node', 'nina', 'field_estado_nina',       'list_string', 'Estado', [],
  ['allowed_values' => ['activa' => 'Activa', 'egresada' => 'Egresada', 'baja' => 'Baja']], TRUE);
ensure_field('node', 'nina', 'field_grado_escolar',     'string',    'Grado escolar');
ensure_field('node', 'nina', 'field_escuela',           'string',    'Escuela');
ensure_field('node', 'nina', 'field_notas_medicas',     'text_long', 'Notas médicas');
ensure_field('node', 'nina', 'field_foto_nina',         'image',     'Fotografía', ['uri_scheme' => 'private']);
ensure_field('node', 'nina', 'field_expediente_num',    'string',    'Número de expediente', [], [], TRUE);

// ═══════════════════════════════════════════════════════════════════
// CAMPOS: necesidad
// ═══════════════════════════════════════════════════════════════════
echo "── Campos para 'necesidad' ──\n";

ensure_field('node', 'necesidad', 'field_categoria_necesidad', 'list_string', 'Categoría', [],
  ['allowed_values' => [
    'ropa'           => 'Ropa y calzado',
    'libros'         => 'Libros y útiles',
    'material_esc'   => 'Material escolar',
    'medicinas'      => 'Medicinas',
    'higiene'        => 'Higiene personal',
    'alimentacion'   => 'Alimentación',
    'electronico'    => 'Equipo electrónico',
    'otro'           => 'Otro',
  ]], TRUE);
ensure_field('node', 'necesidad', 'field_nina_ref',       'entity_reference', 'Niña relacionada',
  ['target_type' => 'node'],
  ['handler' => 'default:node', 'handler_settings' => ['target_bundles' => ['nina' => 'nina']]]);
ensure_field('node', 'necesidad', 'field_urgencia',       'list_string', 'Urgencia', [],
  ['allowed_values' => ['alta' => 'Alta', 'media' => 'Media', 'baja' => 'Baja']], TRUE);
ensure_field('node', 'necesidad', 'field_estado_nec',     'list_string', 'Estado', [],
  ['allowed_values' => ['abierta' => 'Abierta', 'comprometida' => 'Comprometida', 'cubierta' => 'Cubierta']], TRUE);
ensure_field('node', 'necesidad', 'field_cantidad',       'integer',     'Cantidad necesaria');
ensure_field('node', 'necesidad', 'field_imagen_nec',     'image',       'Imagen referencial');

// ═══════════════════════════════════════════════════════════════════
// CAMPOS: donacion
// ═══════════════════════════════════════════════════════════════════
echo "── Campos para 'donacion' ──\n";

ensure_field('node', 'donacion', 'field_necesidad_ref',  'entity_reference', 'Necesidad comprometida',
  ['target_type' => 'node'],
  ['handler' => 'default:node', 'handler_settings' => ['target_bundles' => ['necesidad' => 'necesidad']]]);
ensure_field('node', 'donacion', 'field_benefactor_ref', 'entity_reference', 'Benefactor',
  ['target_type' => 'user'], []);
ensure_field('node', 'donacion', 'field_fecha_compromiso', 'datetime', 'Fecha de compromiso', ['datetime_type' => 'date'], [], TRUE);
ensure_field('node', 'donacion', 'field_fecha_entrega',  'datetime', 'Fecha de entrega real', ['datetime_type' => 'date']);
ensure_field('node', 'donacion', 'field_estado_don',     'list_string', 'Estado', [],
  ['allowed_values' => ['pendiente' => 'Pendiente', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado']], TRUE);
ensure_field('node', 'donacion', 'field_notas_don',      'text_long', 'Notas adicionales');

echo "\n✓ Todos los campos creados.\n";
