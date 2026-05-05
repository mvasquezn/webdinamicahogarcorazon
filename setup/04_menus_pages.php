<?php
/**
 * Creates basic pages and menu structure for El Hogar de Corazón.
 * Run with: drush php:script setup/04_menus_pages.php
 */

use Drupal\node\Entity\Node;
use Drupal\system\Entity\Menu;
use Drupal\menu_link_content\Entity\MenuLinkContent;

// ─── Helper ──────────────────────────────────────────────────────────────────
function create_page(string $title, string $alias, string $body, bool $published = TRUE): int {
  $node = Node::create([
    'type'   => 'page',
    'title'  => $title,
    'body'   => ['value' => $body, 'format' => 'basic_html'],
    'status' => $published ? 1 : 0,
    'path'   => ['alias' => $alias],
  ]);
  $node->save();
  echo "  + Página '$title' → $alias\n";
  return $node->id();
}

function add_menu_link(string $menu, string $title, string $uri, ?string $parent = NULL, int $weight = 0): string {
  $link = MenuLinkContent::create([
    'title'     => $title,
    'link'      => ['uri' => $uri],
    'menu_name' => $menu,
    'expanded'  => TRUE,
    'weight'    => $weight,
  ]);
  if ($parent) $link->set('parent', $parent);
  $link->save();
  return 'menu_link_content:' . $link->uuid();
}

// ═══════════════════════════════════════════════════════════════════
// PÁGINAS PÚBLICAS
// ═══════════════════════════════════════════════════════════════════
echo "── Creando páginas públicas ──\n";

create_page('Inicio', '/inicio',
  '<h2>Bienvenidos a El Hogar de Corazón</h2>
   <p>Somos una institución de asistencia social dedicada a brindar protección, educación y amor a niñas y adolescentes rescatadas de la calle.</p>
   <p>Actualmente contamos con <strong>casi 80 niñas</strong> en nuestras instalaciones. Tu ayuda hace la diferencia.</p>
   <p><a href="/como-ayudar">¡Quiero ayudar!</a> &nbsp; <a href="/registro-benefactor">Registrarme como benefactor</a></p>');

create_page('¿Quiénes somos?', '/quienes-somos',
  '<h2>Nuestra historia</h2>
   <p>El Hogar de Corazón nació hace más de 20 años con una sola misión: dar un hogar seguro y digno a niñas y adolescentes en situación de vulnerabilidad.</p>
   <h3>Misión</h3>
   <p>Brindar protección integral, educación y desarrollo humano a niñas y adolescentes rescatadas de la calle, fomentando su reintegración social.</p>
   <h3>Visión</h3>
   <p>Ser el referente nacional en atención especializada para niñas y adolescentes en riesgo, con un modelo replicable y sostenible.</p>
   <h3>Valores</h3>
   <ul><li>Amor incondicional</li><li>Respeto y dignidad</li><li>Compromiso social</li><li>Transparencia</li></ul>');

create_page('Programas y actividades', '/programas',
  '<h2>Nuestros programas</h2>
   <h3>🎓 Educación</h3>
   <p>Brindamos apoyo escolar completo: desde educación básica hasta preparatoria, con tutorías individualizadas.</p>
   <h3>🏥 Salud integral</h3>
   <p>Atención médica, psicológica y odontológica permanente dentro del hogar.</p>
   <h3>🎨 Arte y cultura</h3>
   <p>Talleres de pintura, música, danza y teatro para el desarrollo creativo y emocional.</p>
   <h3>💼 Capacitación laboral</h3>
   <p>Formación en habilidades para la vida y orientación vocacional para las adolescentes.</p>');

create_page('Historias y testimonios', '/historias',
  '<h2>Historias que inspiran</h2>
   <p>Cada niña que llega a nuestra institución trae consigo una historia de resiliencia. Aquí compartimos algunas de sus travesías (con nombres cambiados para proteger su identidad).</p>
   <p><em>Las noticias y testimonios detallados se publican periódicamente en esta sección.</em></p>');

create_page('Transparencia', '/transparencia',
  '<h2>Transparencia y rendición de cuentas</h2>
   <p>Nos comprometemos a la total transparencia en el manejo de los recursos que la sociedad nos confía.</p>
   <h3>Informe anual 2024</h3>
   <p>Durante 2024 atendimos a 78 niñas, realizamos 1,240 consultas médicas y entregamos 320 kits escolares gracias a nuestros benefactores.</p>
   <h3>¿Cómo se usan los donativos?</h3>
   <ul>
     <li>60% — Alimentación y salud</li>
     <li>25% — Educación y materiales</li>
     <li>10% — Mantenimiento de instalaciones</li>
     <li>5%  — Administración</li>
   </ul>');

create_page('Cómo ayudar', '/como-ayudar',
  '<h2>¡Tu apoyo transforma vidas!</h2>
   <h3>Donar en especie</h3>
   <p>Puedes donar artículos específicos que nuestras niñas necesitan. <a href="/user/register">Regístrate como benefactor</a> para ver el catálogo completo de necesidades.</p>
   <h3>Donar en efectivo</h3>
   <p>Cuenta: BANCO CORAZÓN · Núm. 012-345678-90 · CLABE: 012000001234567890</p>
   <h3>Ser voluntario</h3>
   <p>Si tienes habilidades en educación, salud, arte o administración, ¡te necesitamos! Escríbenos a voluntarios@hogarcorazon.org</p>
   <h3>Convenio empresarial</h3>
   <p>Tu empresa puede convertirse en patrocinadora oficial. Contáctanos para más información.</p>');

create_page('Contacto', '/contacto',
  '<h2>Contáctanos</h2>
   <p><strong>El Hogar de Corazón, A.C.</strong></p>
   <p>📍 Av. de la Esperanza 123, Col. Nueva Vida, Ciudad de México, C.P. 06700</p>
   <p>📞 (55) 5555-1234</p>
   <p>✉️ contacto@hogarcorazon.org</p>
   <p>🕐 Lunes a viernes · 9:00 – 17:00 hrs</p>
   <br>
   <p><em>Usa el formulario de registro para solicitar ser benefactor:</em> <a href="/registro-benefactor">Registro de Benefactor</a></p>');

create_page('Registro de Benefactor', '/registro-benefactor',
  '<h2>Registro de Benefactor</h2>
   <p>Para poder ver las necesidades específicas de las niñas y comprometer donaciones, debes registrarte como benefactor.</p>
   <p>El proceso es:</p>
   <ol>
     <li>Crea tu cuenta usando el formulario de abajo.</li>
     <li>Nuestro equipo revisará tu solicitud (1-2 días hábiles).</li>
     <li>Recibirás un correo de confirmación con acceso al portal de benefactores.</li>
   </ol>
   <a href="/user/register" class="button button--primary">Crear cuenta de Benefactor</a>');

// ═══════════════════════════════════════════════════════════════════
// PÁGINAS ÁREA BENEFACTORES
// ═══════════════════════════════════════════════════════════════════
echo "── Creando páginas área benefactores ──\n";

create_page('Portal del Benefactor', '/benefactor/panel',
  '<h2>Bienvenido al portal de benefactores</h2>
   <p>Gracias por ser parte de El Hogar de Corazón. Desde aquí puedes explorar las necesidades actuales de nuestras niñas y registrar tus compromisos de donación.</p>
   <ul>
     <li><a href="/benefactor/necesidades">Catálogo de necesidades</a> — Explora qué necesitan las niñas</li>
     <li><a href="/benefactor/mis-donaciones">Mis donaciones</a> — Revisa tus compromisos registrados</li>
   </ul>');

create_page('Registrar compromiso de donación', '/benefactor/comprometerse',
  '<h2>Comprometerse con una donación</h2>
   <p>Para registrar tu compromiso de donación a una necesidad específica:</p>
   <ol>
     <li>Visita el <a href="/benefactor/necesidades">catálogo de necesidades</a>.</li>
     <li>Selecciona la necesidad que deseas cubrir y revisa sus detalles.</li>
     <li>Haz clic en <strong>Crear donación</strong> en la página de la necesidad.</li>
   </ol>
   <p>También puedes registrar directamente una donación en efectivo o en especie:</p>
   <a href="/node/add/donacion" class="button button--primary">Registrar donación</a>');

// ═══════════════════════════════════════════════════════════════════
// PÁGINAS ÁREA INTERNA
// ═══════════════════════════════════════════════════════════════════
echo "── Creando páginas área interna ──\n";

create_page('Reportes internos', '/interno/reportes',
  '<h2>Centro de reportes</h2>
   <p>Panel central para el equipo de El Hogar de Corazón.</p>
   <ul>
     <li><a href="/interno/ninas">Gestión de niñas</a> — Altas, salidas y bajas de expedientes</li>
     <li><a href="/interno/necesidades">Gestión de necesidades</a> — Crear, editar y cerrar necesidades</li>
     <li><a href="/interno/benefactores">Gestión de benefactores</a> — Alta, aprobación y bloqueo de cuentas</li>
   </ul>');

// ═══════════════════════════════════════════════════════════════════
// MENÚ PRINCIPAL
// ═══════════════════════════════════════════════════════════════════
echo "── Configurando menú principal ──\n";

// Remove default home link
\Drupal::entityTypeManager()->getStorage('menu_link_content')
  ->loadByProperties(['menu_name' => 'main']) ;

$w = 0;
add_menu_link('main', 'Inicio',                  'internal:/inicio',              NULL, $w++);
add_menu_link('main', '¿Quiénes somos?',         'internal:/quienes-somos',       NULL, $w++);
add_menu_link('main', 'Programas y actividades', 'internal:/programas',           NULL, $w++);
add_menu_link('main', 'Historias',               'internal:/historias',           NULL, $w++);
add_menu_link('main', 'Transparencia',           'internal:/transparencia',       NULL, $w++);
add_menu_link('main', 'Cómo ayudar',             'internal:/como-ayudar',         NULL, $w++);
add_menu_link('main', 'Contacto',                'internal:/contacto',            NULL, $w++);
add_menu_link('main', 'Ser Benefactor',          'internal:/registro-benefactor', NULL, $w++);

echo "\n✓ Páginas y menú configurados.\n";
