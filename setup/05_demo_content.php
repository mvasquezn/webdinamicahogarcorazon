<?php
/**
 * Creates demo data: users, niñas, necesidades, donaciones and news.
 * Run with: drush php:script setup/05_demo_content.php
 */

use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

// ─── Demo users ───────────────────────────────────────────────────────────────
echo "── Creando usuarios demo ──\n";

function create_user(string $name, string $mail, string $pass, string $role): object {
  $existing = \Drupal::entityTypeManager()->getStorage('user')
    ->loadByProperties(['name' => $name]);
  if ($existing) return reset($existing);

  $user = User::create([
    'name'   => $name,
    'mail'   => $mail,
    'pass'   => $pass,
    'status' => 1,
    'roles'  => [$role],
  ]);
  $user->save();
  echo "  + Usuario '$name' ($role)\n";
  return $user;
}

$mayra    = create_user('mayra',       'mayra@hogarcorazon.org',    'Mayra2024!',    'personal_interno');
$dir1     = create_user('directora',   'directora@hogarcorazon.org','Dir2024!',      'director');
$benef1   = create_user('juan_garcia', 'juan@empresa.com',          'Benef2024!',    'benefactor');
$benef2   = create_user('maria_lopez', 'maria@donante.mx',          'Benef2024!',    'benefactor');
$benef3   = create_user('corp_ayuda',  'donativos@corpayuda.com',   'Benef2024!',    'benefactor');

// ─── Niñas ────────────────────────────────────────────────────────────────────
echo "── Creando expedientes de niñas ──\n";

$ninas_data = [
  ['Sofía R.',    'EXP-001', '2021-03-15', NULL, 'activa',   '4° Primaria',  'Esc. Benito Juárez'],
  ['Valentina M.','EXP-002', '2020-07-22', NULL, 'activa',   '1° Secundaria','Sec. Lázaro Cárdenas'],
  ['Camila T.',   'EXP-003', '2019-11-08', NULL, 'activa',   '3° Secundaria','Sec. Lázaro Cárdenas'],
  ['Isabella P.', 'EXP-004', '2022-01-30', NULL, 'activa',   '2° Primaria',  'Esc. Benito Juárez'],
  ['Fernanda L.', 'EXP-005', '2023-06-12', NULL, 'activa',   '5° Primaria',  'Esc. Benito Juárez'],
  ['Lucía H.',    'EXP-006', '2018-09-01', '2024-08-15', 'egresada', '1° Prepa', 'CBTis 45'],
  ['Daniela G.',  'EXP-007', '2021-04-20', NULL, 'activa',   '6° Primaria',  'Esc. Benito Juárez'],
  ['Natalia V.',  'EXP-008', '2022-10-05', NULL, 'activa',   'Preescolar',   'Jardín de Niños Flores'],
  ['Renata O.',   'EXP-009', '2020-02-14', NULL, 'activa',   '2° Secundaria','Sec. Lázaro Cárdenas'],
  ['Emilia C.',   'EXP-010', '2023-08-28', NULL, 'activa',   '3° Primaria',  'Esc. Benito Juárez'],
];

$nina_ids = [];
foreach ($ninas_data as [$nombre, $exp, $ingreso, $salida, $estado, $grado, $escuela]) {
  $node = Node::create([
    'type'                    => 'nina',
    'title'                   => $nombre,
    'status'                  => 1,
    'field_expediente_num'    => $exp,
    'field_fecha_ingreso'     => ['value' => $ingreso],
    'field_fecha_salida'      => $salida ? ['value' => $salida] : [],
    'field_estado_nina'       => $estado,
    'field_grado_escolar'     => $grado,
    'field_escuela'           => $escuela,
    'field_notas_medicas'     => ['value' => 'Sin observaciones médicas relevantes.', 'format' => 'plain_text'],
  ]);
  $node->save();
  $nina_ids[$exp] = $node->id();
  echo "  + Niña '$nombre' ($exp)\n";
}

// ─── Necesidades ─────────────────────────────────────────────────────────────
echo "── Creando necesidades ──\n";

$nec_data = [
  ['Uniforme escolar completo para Sofía',    'ropa',         'EXP-001', 'alta',  'abierta',  2],
  ['Libros de texto 4° primaria',             'libros',       'EXP-001', 'media', 'abierta',  4],
  ['Útiles escolares para Valentina',         'material_esc', 'EXP-002', 'alta',  'comprometida', 1],
  ['Medicamento para la tos (Ambroxol)',      'medicinas',    'EXP-003', 'alta',  'cubierta', 2],
  ['Zapatos talla 28 para Isabella',          'ropa',         'EXP-004', 'media', 'abierta',  1],
  ['Kit de higiene personal x10',             'higiene',      'EXP-005', 'media', 'abierta',  10],
  ['Mochila escolar para Fernanda',           'material_esc', 'EXP-005', 'baja',  'abierta',  1],
  ['Cuadernos (pack 10 pzas) para Daniela',   'material_esc', 'EXP-007', 'alta',  'abierta',  3],
  ['Ropa de invierno para Natalia',           'ropa',         'EXP-008', 'alta',  'comprometida', 5],
  ['Vitaminas infantiles 3 meses',            'medicinas',    'EXP-008', 'media', 'abierta',  3],
  ['Libros de lectura para Renata',           'libros',       'EXP-009', 'baja',  'abierta',  5],
  ['Colores y materiales de arte',            'material_esc', 'EXP-010', 'baja',  'abierta',  1],
];

$nec_ids = [];
foreach ($nec_data as $i => [$titulo, $cat, $nina_exp, $urgencia, $estado, $cantidad]) {
  $node = Node::create([
    'type'                      => 'necesidad',
    'title'                     => $titulo,
    'status'                    => 1,
    'field_categoria_necesidad' => $cat,
    'field_nina_ref'            => ['target_id' => $nina_ids[$nina_exp]],
    'field_urgencia'            => $urgencia,
    'field_estado_nec'          => $estado,
    'field_cantidad'            => $cantidad,
    'body'                      => ['value' => "Necesidad para la niña con expediente $nina_exp. Estado actual: $estado.", 'format' => 'basic_html'],
  ]);
  $node->save();
  $nec_ids[] = $node->id();
  echo "  + Necesidad: $titulo\n";
}

// ─── Donaciones ───────────────────────────────────────────────────────────────
echo "── Creando donaciones demo ──\n";

$donaciones = [
  [$benef1->id(), $nec_ids[2], '2025-04-10', '2025-04-20', 'entregado', 'Entregado personalmente en el hogar.'],
  [$benef2->id(), $nec_ids[3], '2025-04-15', '2025-04-18', 'entregado', 'Donación de medicamento cubierta completamente.'],
  [$benef3->id(), $nec_ids[8], '2025-05-01', NULL,         'pendiente', 'El donativo está programado para entrega esta semana.'],
  [$benef1->id(), $nec_ids[0], '2025-05-02', NULL,         'pendiente', 'Compromiso de donar el uniforme la próxima semana.'],
];

foreach ($donaciones as [$uid, $nec_id, $fecha_comp, $fecha_ent, $estado, $nota]) {
  $node = Node::create([
    'type'                  => 'donacion',
    'title'                 => 'Donación ' . date('Y-m-d'),
    'status'                => 1,
    'field_necesidad_ref'   => ['target_id' => $nec_id],
    'field_benefactor_ref'  => ['target_id' => $uid],
    'field_fecha_compromiso'=> ['value' => $fecha_comp],
    'field_fecha_entrega'   => $fecha_ent ? ['value' => $fecha_ent] : [],
    'field_estado_don'      => $estado,
    'field_notas_don'       => ['value' => $nota, 'format' => 'plain_text'],
  ]);
  $node->save();
  echo "  + Donación (estado: $estado)\n";
}

// ─── Noticias / Testimonios ───────────────────────────────────────────────────
echo "── Creando noticias y testimonios ──\n";

$noticias = [
  [
    'Una nueva oportunidad para Lucía',
    '/historias/lucia',
    '<p>"Cuando llegué al hogar tenía miedo de todo. Hoy me gradué de secundaria y tengo sueños." — Lucía, 16 años.</p>
     <p>Lucía llegó al hogar en 2018 con apenas 10 años. Gracias a los programas de atención psicológica y educativa, logró superar sus traumas y concluir su educación secundaria.</p>',
  ],
  [
    'Donativo de uniformes: 30 niñas beneficiadas',
    '/historias/donativo-uniformes-2025',
    '<p>En abril de 2025, la empresa <strong>CorpAyuda S.A.</strong> donó 30 uniformes escolares completos para nuestras niñas.</p>
     <p>Este tipo de apoyos nos permiten garantizar que cada niña ingrese al año escolar con todo lo necesario.</p>',
  ],
  [
    'Taller de pintura: arte que sana',
    '/historias/taller-pintura-2025',
    '<p>El pasado marzo inauguramos nuestro nuevo taller de pintura artística, impartido por voluntarios de la comunidad artística local.</p>
     <p>15 niñas participan semanalmente en esta actividad que ha mostrado resultados positivos en su desarrollo emocional.</p>',
  ],
];

foreach ($noticias as [$titulo, $alias, $body]) {
  $node = Node::create([
    'type'   => 'article',
    'title'  => $titulo,
    'status' => 1,
    'body'   => ['value' => $body, 'format' => 'basic_html'],
    'path'   => ['alias' => $alias],
  ]);
  $node->save();
  echo "  + Noticia: $titulo\n";
}

echo "\n✓ Contenido demo creado.\n";
echo "\n── Credenciales de acceso ──────────────────────────\n";
echo "  Administrador:   admin / Admin2024!\n";
echo "  Directora:       directora / Dir2024!\n";
echo "  Personal interno:mayra / Mayra2024!\n";
echo "  Benefactor 1:    juan_garcia / Benef2024!\n";
echo "  Benefactor 2:    maria_lopez / Benef2024!\n";
echo "  Benefactor 3:    corp_ayuda  / Benef2024!\n";
echo "────────────────────────────────────────────────────\n";
