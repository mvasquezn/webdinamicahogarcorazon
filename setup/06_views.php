<?php
/**
 * Creates Views for Catalogo de Necesidades, Panel Benefactor, Reportes.
 * Run with: drush php:script setup/06_views.php
 */

use Drupal\views\Entity\View;

// ─── Helper ──────────────────────────────────────────────────────────────────
function view_exists(string $id): bool {
  return (bool) View::load($id);
}

// ═══════════════════════════════════════════════════════════════════
// VIEW: Catálogo de Necesidades (para benefactores)
// ═══════════════════════════════════════════════════════════════════
if (!view_exists('catalogo_necesidades')) {
  $view = View::create([
    'id'          => 'catalogo_necesidades',
    'label'       => 'Catálogo de Necesidades',
    'base_table'  => 'node_field_data',
    'description' => 'Lista de necesidades abiertas visibles para benefactores.',
    'status'      => TRUE,
    'display'     => [
      'default' => [
        'display_plugin' => 'default',
        'id'             => 'default',
        'display_title'  => 'Default',
        'position'       => 0,
        'display_options' => [
          'title' => 'Catálogo de Necesidades',
          'fields' => [
            'title' => [
              'id' => 'title', 'table' => 'node_field_data', 'field' => 'title',
              'label' => 'Necesidad', 'alter' => ['make_link' => TRUE],
              'plugin_id' => 'field',
            ],
            'field_categoria_necesidad' => [
              'id' => 'field_categoria_necesidad', 'table' => 'node__field_categoria_necesidad',
              'field' => 'field_categoria_necesidad', 'label' => 'Categoría', 'plugin_id' => 'field',
            ],
            'field_urgencia' => [
              'id' => 'field_urgencia', 'table' => 'node__field_urgencia',
              'field' => 'field_urgencia', 'label' => 'Urgencia', 'plugin_id' => 'field',
            ],
            'field_cantidad' => [
              'id' => 'field_cantidad', 'table' => 'node__field_cantidad',
              'field' => 'field_cantidad', 'label' => 'Cantidad', 'plugin_id' => 'field',
            ],
            'field_estado_nec' => [
              'id' => 'field_estado_nec', 'table' => 'node__field_estado_nec',
              'field' => 'field_estado_nec', 'label' => 'Estado', 'plugin_id' => 'field',
            ],
          ],
          'filters' => [
            'status' => [
              'id' => 'status', 'table' => 'node_field_data', 'field' => 'status',
              'value' => '1', 'plugin_id' => 'boolean',
            ],
            'type' => [
              'id' => 'type', 'table' => 'node_field_data', 'field' => 'type',
              'value' => ['necesidad' => 'necesidad'], 'plugin_id' => 'bundle',
            ],
            'field_estado_nec_value' => [
              'id' => 'field_estado_nec_value', 'table' => 'node__field_estado_nec',
              'field' => 'field_estado_nec_value',
              'value' => ['abierta' => 'abierta', 'comprometida' => 'comprometida'],
              'plugin_id' => 'list_field',
            ],
          ],
          'sorts' => [
            'field_urgencia_value' => [
              'id' => 'field_urgencia_value', 'table' => 'node__field_urgencia',
              'field' => 'field_urgencia_value', 'order' => 'ASC', 'plugin_id' => 'standard',
            ],
          ],
          'access' => ['type' => 'role', 'options' => ['role' => ['benefactor' => 'benefactor', 'personal_interno' => 'personal_interno', 'director' => 'director', 'administrator' => 'administrator']]],
          'style'  => ['type' => 'table'],
          'row'    => ['type' => 'fields'],
          'pager'  => ['type' => 'mini', 'options' => ['items_per_page' => 20]],
          'use_more' => FALSE,
        ],
      ],
      'page_benefactor' => [
        'display_plugin' => 'page',
        'id'             => 'page_benefactor',
        'display_title'  => 'Página Benefactor',
        'position'       => 1,
        'display_options' => [
          'path' => 'benefactor/necesidades',
        ],
      ],
    ],
  ]);
  $view->save();
  echo "✓ Vista 'catalogo_necesidades' creada.\n";
}

// ═══════════════════════════════════════════════════════════════════
// VIEW: Gestión de Niñas (área interna)
// ═══════════════════════════════════════════════════════════════════
if (!view_exists('gestion_ninas')) {
  $view = View::create([
    'id'         => 'gestion_ninas',
    'label'      => 'Gestión de Niñas',
    'base_table' => 'node_field_data',
    'status'     => TRUE,
    'display' => [
      'default' => [
        'display_plugin' => 'default',
        'id'             => 'default',
        'display_title'  => 'Default',
        'position'       => 0,
        'display_options' => [
          'title' => 'Gestión de Niñas',
          'fields' => [
            'title'                => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'label' => 'Nombre', 'plugin_id' => 'field'],
            'field_expediente_num' => ['id' => 'field_expediente_num', 'table' => 'node__field_expediente_num', 'field' => 'field_expediente_num', 'label' => 'Expediente', 'plugin_id' => 'field'],
            'field_fecha_ingreso'  => ['id' => 'field_fecha_ingreso', 'table' => 'node__field_fecha_ingreso', 'field' => 'field_fecha_ingreso_value', 'label' => 'Ingreso', 'plugin_id' => 'field'],
            'field_estado_nina'    => ['id' => 'field_estado_nina', 'table' => 'node__field_estado_nina', 'field' => 'field_estado_nina_value', 'label' => 'Estado', 'plugin_id' => 'field'],
            'field_grado_escolar'  => ['id' => 'field_grado_escolar', 'table' => 'node__field_grado_escolar', 'field' => 'field_grado_escolar_value', 'label' => 'Grado', 'plugin_id' => 'field'],
            'edit_node'            => ['id' => 'edit_node', 'table' => 'views', 'field' => 'edit_node', 'label' => '', 'plugin_id' => 'node_link_edit'],
          ],
          'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean'],
            'type'   => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['nina' => 'nina'], 'plugin_id' => 'bundle'],
          ],
          'filter_groups' => ['operator' => 'AND', 'groups' => [1 => 'AND']],
          'access' => ['type' => 'role', 'options' => ['role' => ['personal_interno' => 'personal_interno', 'director' => 'director', 'administrator' => 'administrator']]],
          'style'  => ['type' => 'table'],
          'row'    => ['type' => 'fields'],
          'pager'  => ['type' => 'full', 'options' => ['items_per_page' => 25]],
          'exposed_form' => ['type' => 'basic'],
        ],
      ],
      'page_interna' => [
        'display_plugin' => 'page',
        'id'             => 'page_interna',
        'display_title'  => 'Página Interna',
        'position'       => 1,
        'display_options' => [
          'path' => 'interno/ninas',
        ],
      ],
    ],
  ]);
  $view->save();
  echo "✓ Vista 'gestion_ninas' creada.\n";
}

// ═══════════════════════════════════════════════════════════════════
// VIEW: Mis Donaciones (para benefactores)
// ═══════════════════════════════════════════════════════════════════
if (!view_exists('mis_donaciones')) {
  $view = View::create([
    'id'         => 'mis_donaciones',
    'label'      => 'Mis Donaciones',
    'base_table' => 'node_field_data',
    'status'     => TRUE,
    'display' => [
      'default' => [
        'display_plugin' => 'default',
        'id'             => 'default',
        'display_title'  => 'Default',
        'position'       => 0,
        'display_options' => [
          'title' => 'Mis Donaciones',
          'fields' => [
            'title'                  => ['id' => 'title', 'table' => 'node_field_data', 'field' => 'title', 'label' => 'Donación', 'plugin_id' => 'field'],
            'field_fecha_compromiso' => ['id' => 'field_fecha_compromiso', 'table' => 'node__field_fecha_compromiso', 'field' => 'field_fecha_compromiso_value', 'label' => 'Fecha compromiso', 'plugin_id' => 'field'],
            'field_estado_don'       => ['id' => 'field_estado_don', 'table' => 'node__field_estado_don', 'field' => 'field_estado_don_value', 'label' => 'Estado', 'plugin_id' => 'field'],
          ],
          'filters' => [
            'status' => ['id' => 'status', 'table' => 'node_field_data', 'field' => 'status', 'value' => '1', 'plugin_id' => 'boolean'],
            'type'   => ['id' => 'type', 'table' => 'node_field_data', 'field' => 'type', 'value' => ['donacion' => 'donacion'], 'plugin_id' => 'bundle'],
            'field_benefactor_ref_target_id' => [
              'id' => 'field_benefactor_ref_target_id', 'table' => 'node__field_benefactor_ref',
              'field' => 'field_benefactor_ref_target_id',
              'relationship' => 'none',
              'value' => ['value' => '***CURRENT_USER***'],
              'plugin_id' => 'numeric',
            ],
          ],
          'access' => ['type' => 'role', 'options' => ['role' => ['benefactor' => 'benefactor', 'administrator' => 'administrator']]],
          'style'  => ['type' => 'table'],
          'row'    => ['type' => 'fields'],
        ],
      ],
      'page_mis_donaciones' => [
        'display_plugin' => 'page',
        'id'             => 'page_mis_donaciones',
        'display_title'  => 'Página Mis Donaciones',
        'position'       => 1,
        'display_options' => ['path' => 'benefactor/mis-donaciones'],
      ],
    ],
  ]);
  $view->save();
  echo "✓ Vista 'mis_donaciones' creada.\n";
}

// ═══════════════════════════════════════════════════════════════════
// VIEW: Gestión de Necesidades (área interna)
// ═══════════════════════════════════════════════════════════════════
if (!view_exists('gestion_necesidades')) {
  $view = View::create([
    'id'         => 'gestion_necesidades',
    'label'      => 'Gestión de Necesidades',
    'base_table' => 'node_field_data',
    'status'     => TRUE,
    'display' => [
      'default' => [
        'display_plugin' => 'default',
        'id'             => 'default',
        'display_title'  => 'Default',
        'position'       => 0,
        'display_options' => [
          'title' => 'Gestión de Necesidades',
          'fields' => [
            'title' => [
              'id' => 'title', 'table' => 'node_field_data', 'field' => 'title',
              'label' => 'Necesidad', 'alter' => ['make_link' => TRUE], 'plugin_id' => 'field',
            ],
            'field_categoria_necesidad' => [
              'id' => 'field_categoria_necesidad', 'table' => 'node__field_categoria_necesidad',
              'field' => 'field_categoria_necesidad', 'label' => 'Categoría', 'plugin_id' => 'field',
            ],
            'field_urgencia' => [
              'id' => 'field_urgencia', 'table' => 'node__field_urgencia',
              'field' => 'field_urgencia', 'label' => 'Urgencia', 'plugin_id' => 'field',
            ],
            'field_cantidad' => [
              'id' => 'field_cantidad', 'table' => 'node__field_cantidad',
              'field' => 'field_cantidad', 'label' => 'Cantidad', 'plugin_id' => 'field',
            ],
            'field_estado_nec' => [
              'id' => 'field_estado_nec', 'table' => 'node__field_estado_nec',
              'field' => 'field_estado_nec', 'label' => 'Estado', 'plugin_id' => 'field',
            ],
            'edit_node' => [
              'id' => 'edit_node', 'table' => 'views', 'field' => 'edit_node',
              'label' => '', 'plugin_id' => 'node_link_edit',
            ],
          ],
          'filters' => [
            'status' => [
              'id' => 'status', 'table' => 'node_field_data', 'field' => 'status',
              'value' => '1', 'plugin_id' => 'boolean',
            ],
            'type' => [
              'id' => 'type', 'table' => 'node_field_data', 'field' => 'type',
              'value' => ['necesidad' => 'necesidad'], 'plugin_id' => 'bundle',
            ],
          ],
          'sorts' => [
            'field_urgencia_value' => [
              'id' => 'field_urgencia_value', 'table' => 'node__field_urgencia',
              'field' => 'field_urgencia_value', 'order' => 'ASC', 'plugin_id' => 'standard',
            ],
          ],
          'access' => ['type' => 'role', 'options' => ['role' => [
            'personal_interno' => 'personal_interno',
            'director'         => 'director',
            'administrator'    => 'administrator',
          ]]],
          'style'        => ['type' => 'table'],
          'row'          => ['type' => 'fields'],
          'pager'        => ['type' => 'full', 'options' => ['items_per_page' => 25]],
          'exposed_form' => ['type' => 'basic'],
          'use_more'     => FALSE,
        ],
      ],
      'page_interna' => [
        'display_plugin' => 'page',
        'id'             => 'page_interna',
        'display_title'  => 'Página Interna',
        'position'       => 1,
        'display_options' => ['path' => 'interno/necesidades'],
      ],
    ],
  ]);
  $view->save();
  echo "✓ Vista 'gestion_necesidades' creada → /interno/necesidades\n";
}

// ═══════════════════════════════════════════════════════════════════
// VIEW: Gestión de Benefactores (área interna — base: usuarios)
// ═══════════════════════════════════════════════════════════════════
if (!view_exists('gestion_benefactores')) {
  $view = View::create([
    'id'         => 'gestion_benefactores',
    'label'      => 'Gestión de Benefactores',
    'base_table' => 'users_field_data',
    'status'     => TRUE,
    'display' => [
      'default' => [
        'display_plugin' => 'default',
        'id'             => 'default',
        'display_title'  => 'Default',
        'position'       => 0,
        'display_options' => [
          'title' => 'Gestión de Benefactores',
          'fields' => [
            'name' => [
              'id' => 'name', 'table' => 'users_field_data', 'field' => 'name',
              'label' => 'Usuario', 'plugin_id' => 'field',
            ],
            'mail' => [
              'id' => 'mail', 'table' => 'users_field_data', 'field' => 'mail',
              'label' => 'Correo electrónico', 'plugin_id' => 'field',
            ],
            'created' => [
              'id' => 'created', 'table' => 'users_field_data', 'field' => 'created',
              'label' => 'Registrado', 'plugin_id' => 'date',
              'date_format' => 'short',
            ],
            'status' => [
              'id' => 'status', 'table' => 'users_field_data', 'field' => 'status',
              'label' => 'Activo', 'plugin_id' => 'boolean',
            ],
            'edit_user' => [
              'id' => 'edit_user', 'table' => 'views', 'field' => 'edit_user',
              'label' => '', 'plugin_id' => 'user_link_edit',
            ],
          ],
          'filters' => [
            'roles_target_id' => [
              'id' => 'roles_target_id', 'table' => 'user__roles', 'field' => 'roles_target_id',
              'value' => ['benefactor' => 'benefactor'], 'plugin_id' => 'user_roles',
            ],
          ],
          'sorts' => [
            'created' => [
              'id' => 'created', 'table' => 'users_field_data',
              'field' => 'created', 'order' => 'DESC', 'plugin_id' => 'date',
            ],
          ],
          'access' => ['type' => 'role', 'options' => ['role' => [
            'personal_interno' => 'personal_interno',
            'director'         => 'director',
            'administrator'    => 'administrator',
          ]]],
          'style'    => ['type' => 'table'],
          'row'      => ['type' => 'fields'],
          'pager'    => ['type' => 'full', 'options' => ['items_per_page' => 25]],
          'use_more' => FALSE,
        ],
      ],
      'page_interna' => [
        'display_plugin' => 'page',
        'id'             => 'page_interna',
        'display_title'  => 'Página Interna',
        'position'       => 1,
        'display_options' => ['path' => 'interno/benefactores'],
      ],
    ],
  ]);
  $view->save();
  echo "✓ Vista 'gestion_benefactores' creada → /interno/benefactores\n";
}

echo "\n✓ Vistas configuradas.\n";
