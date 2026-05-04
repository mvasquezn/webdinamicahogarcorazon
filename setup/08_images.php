<?php
/**
 * Registers images in Drupal's file system and updates page/article bodies.
 * Run with: drush php:script setup/08_images.php
 */

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;

$IMG_BASE = '/sites/default/files/hogar-images/';
$IMG_PATH = '/var/www/html/web/sites/default/files/hogar-images/';

/**
 * Creates a managed file entity from an already-present physical file.
 */
function register_file(string $path, string $uri_base): ?File {
  $filename = basename($path);
  $uri      = 'public://hogar-images/' . $filename;

  // Check if already registered
  $existing = \Drupal::entityTypeManager()
    ->getStorage('file')
    ->loadByProperties(['uri' => $uri]);
  if ($existing) return reset($existing);

  $file = File::create([
    'uri'      => $uri,
    'filename' => $filename,
    'filemime' => 'image/jpeg',
    'status'   => 1,
  ]);
  $file->save();
  return $file;
}

// ─── Register all images ──────────────────────────────────────
$images = [
  'hero'              => 'hero.jpg',
  'quienes-somos'     => 'quienes-somos.jpg',
  'educacion'         => 'educacion.jpg',
  'salud'             => 'salud.jpg',
  'arte'              => 'arte.jpg',
  'vocacional'        => 'vocacional.jpg',
  'testimonio-lucia'  => 'testimonio-lucia.jpg',
  'donativo'          => 'donativo-uniformes.jpg',
  'taller'            => 'taller-pintura.jpg',
  'transparencia'     => 'transparencia.jpg',
  'como-ayudar'       => 'como-ayudar.jpg',
  'contacto'          => 'contacto.jpg',
];

$fids = [];
foreach ($images as $key => $file) {
  $f = register_file($IMG_PATH . $file, $IMG_BASE);
  if ($f) {
    $fids[$key] = $f->id();
    echo "  ✓ $key (fid:{$f->id()})\n";
  }
}

// ─── Helper: img tag ─────────────────────────────────────────
function img(string $key, string $alt, string $caption = '', string $float = ''): string {
  $src = '/sites/default/files/hogar-images/' . $GLOBALS['_img_map'][$key];
  $style = $float ? "float:$float;margin:" . ($float === 'right' ? '0 0 1.2rem 1.8rem' : '0 1.8rem 1.2rem 0') . ';max-width:42%;border-radius:10px;box-shadow:0 4px 18px rgba(90,42,106,.18);' : 'width:100%;border-radius:10px;box-shadow:0 4px 18px rgba(90,42,106,.18);margin-bottom:1.5rem;';
  $html = "<img src=\"$src\" alt=\"$alt\" style=\"$style\">";
  if ($caption) $html .= "<p style=\"font-size:.82rem;color:#7A6080;text-align:center;margin-top:-.8rem;margin-bottom:1.2rem;\">$caption</p>";
  return $html;
}
$GLOBALS['_img_map'] = $images;

// ─── Update pages ─────────────────────────────────────────────
echo "\n── Actualizando páginas ──\n";

// INICIO
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => 'Inicio']);
if ($node = reset($node)) {
  $hero = img('hero', 'Niñas del Hogar de Corazón estudiando juntas', 'Más de 80 niñas reciben atención integral en nuestro hogar.');
  $body = $hero . '
<h2>Bienvenidos a El Hogar de Corazón</h2>
<p>Somos una institución de asistencia social dedicada a brindar protección, educación y amor a niñas y adolescentes rescatadas de la calle.</p>
<p>Actualmente contamos con <strong>casi 80 niñas</strong> en nuestras instalaciones. Tu ayuda hace la diferencia.</p>
<p style="margin-top:1.5rem;">
  <a href="/como-ayudar" style="background:#E8913A;color:#fff;padding:.7rem 1.5rem;border-radius:6px;text-decoration:none;font-weight:600;margin-right:.8rem;">¡Quiero ayudar!</a>
  <a href="/registro-benefactor" style="background:#7C3D8F;color:#fff;padding:.7rem 1.5rem;border-radius:6px;text-decoration:none;font-weight:600;">Ser Benefactor</a>
</p>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Inicio\n";
}

// QUIÉNES SOMOS
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => '¿Quiénes somos?']);
if ($node = reset($node)) {
  $pic = img('quienes-somos', 'Equipo del Hogar de Corazón', '', 'right');
  $body = $pic . '
<h2>Nuestra historia</h2>
<p>El Hogar de Corazón nació hace más de 20 años con una sola misión: dar un hogar seguro y digno a niñas y adolescentes en situación de vulnerabilidad.</p>
<h3>Misión</h3>
<p>Brindar protección integral, educación y desarrollo humano a niñas y adolescentes rescatadas de la calle, fomentando su reintegración social.</p>
<h3>Visión</h3>
<p>Ser el referente nacional en atención especializada para niñas y adolescentes en riesgo, con un modelo replicable y sostenible.</p>
<h3>Valores</h3>
<ul><li>Amor incondicional</li><li>Respeto y dignidad</li><li>Compromiso social</li><li>Transparencia</li></ul>
<div style="clear:both"></div>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Quiénes somos\n";
}

// PROGRAMAS Y ACTIVIDADES
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => 'Programas y actividades']);
if ($node = reset($node)) {
  $body = '<h2>Nuestros programas</h2>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;margin-bottom:2rem;">

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(90,42,106,.15);">
    <img src="/sites/default/files/hogar-images/educacion.jpg" alt="Educación" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.2rem;">
      <h3 style="margin-top:0;">🎓 Educación</h3>
      <p>Apoyo escolar completo desde preescolar hasta preparatoria, con tutorías individualizadas y útiles garantizados.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(90,42,106,.15);">
    <img src="/sites/default/files/hogar-images/salud.jpg" alt="Salud" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.2rem;">
      <h3 style="margin-top:0;">🏥 Salud integral</h3>
      <p>Atención médica, psicológica y odontológica permanente dentro del hogar, sin costo para las niñas.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(90,42,106,.15);">
    <img src="/sites/default/files/hogar-images/arte.jpg" alt="Arte y cultura" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.2rem;">
      <h3 style="margin-top:0;">🎨 Arte y cultura</h3>
      <p>Talleres de pintura, música, danza y teatro para el desarrollo creativo y emocional de cada niña.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(90,42,106,.15);">
    <img src="/sites/default/files/hogar-images/vocacional.jpg" alt="Capacitación" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.2rem;">
      <h3 style="margin-top:0;">💼 Capacitación laboral</h3>
      <p>Formación en habilidades para la vida y orientación vocacional para las adolescentes mayores.</p>
    </div>
  </div>

</div>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Programas y actividades\n";
}

// TRANSPARENCIA
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => 'Transparencia']);
if ($node = reset($node)) {
  $pic = img('transparencia', 'Informe de transparencia y rendición de cuentas', 'Publicamos nuestros informes anualmente.', 'right');
  $body = $pic . '
<h2>Transparencia y rendición de cuentas</h2>
<p>Nos comprometemos a la total transparencia en el manejo de los recursos que la sociedad nos confía.</p>
<h3>Informe anual 2024</h3>
<p>Durante 2024 atendimos a 78 niñas, realizamos 1,240 consultas médicas y entregamos 320 kits escolares gracias a nuestros benefactores.</p>
<h3>¿Cómo se usan los donativos?</h3>
<ul>
  <li><strong>60%</strong> — Alimentación y salud</li>
  <li><strong>25%</strong> — Educación y materiales</li>
  <li><strong>10%</strong> — Mantenimiento de instalaciones</li>
  <li><strong>5%</strong>  — Administración</li>
</ul>
<div style="clear:both"></div>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Transparencia\n";
}

// CÓMO AYUDAR
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => 'Cómo ayudar']);
if ($node = reset($node)) {
  $pic = img('como-ayudar', 'Voluntarios apoyando al hogar', 'Tu tiempo y recursos transforman vidas.', 'left');
  $body = $pic . '
<h2>¡Tu apoyo transforma vidas!</h2>
<h3>Donar en especie</h3>
<p>Puedes donar artículos específicos que nuestras niñas necesitan. <a href="/registro-benefactor">Regístrate como benefactor</a> para ver el catálogo completo de necesidades.</p>
<h3>Donar en efectivo</h3>
<p>Cuenta: BANCO CORAZÓN · Núm. 012-345678-90 · CLABE: 012000001234567890</p>
<h3>Ser voluntario</h3>
<p>Si tienes habilidades en educación, salud, arte o administración, ¡te necesitamos! Escríbenos a voluntarios@hogarcorazon.org</p>
<div style="clear:both"></div>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Cómo ayudar\n";
}

// CONTACTO
$node = \Drupal::entityTypeManager()->getStorage('node')
  ->loadByProperties(['type' => 'page', 'title' => 'Contacto']);
if ($node = reset($node)) {
  $pic = img('contacto', 'Oficinas del Hogar de Corazón', '', 'right');
  $body = $pic . '
<h2>Contáctanos</h2>
<p><strong>El Hogar de Corazón, A.C.</strong></p>
<p>📍 Av. de la Esperanza 123, Col. Nueva Vida, Ciudad de México, C.P. 06700</p>
<p>📞 (55) 5555-1234</p>
<p>✉️ contacto@hogarcorazon.org</p>
<p>🕐 Lunes a viernes · 9:00 – 17:00 hrs</p>
<div style="clear:both"></div>
<p><em>Usa el formulario de registro para solicitar ser benefactor:</em> <a href="/registro-benefactor">Registro de Benefactor</a></p>';
  $node->body->value  = $body;
  $node->body->format = 'full_html';
  $node->save();
  echo "  ✓ Contacto\n";
}

// ─── Update news / testimonials ───────────────────────────────
echo "\n── Actualizando noticias ──\n";

$noticias = [
  'Una nueva oportunidad para Lucía' => [
    'img' => 'testimonio-lucia',
    'alt' => 'Joven estudiando con determinación',
    'body' => '<img src="/sites/default/files/hogar-images/testimonio-lucia.jpg" alt="Joven estudiando con determinación" style="width:100%;max-height:380px;object-fit:cover;border-radius:10px;margin-bottom:1.2rem;box-shadow:0 4px 18px rgba(90,42,106,.18);">
<p>"Cuando llegué al hogar tenía miedo de todo. Hoy me gradué de secundaria y tengo sueños." — <em>Lucía, 16 años.</em></p>
<p>Lucía llegó al hogar en 2018 con apenas 10 años. Gracias a los programas de atención psicológica y educativa, logró superar sus traumas y concluir su educación secundaria. Hoy estudia preparatoria y sueña con ser enfermera.</p>
<p>Su historia es el reflejo de lo que es posible cuando una comunidad decide cuidar a sus niñas.</p>',
  ],
  'Donativo de uniformes: 30 niñas beneficiadas' => [
    'img' => 'donativo',
    'alt' => 'Donativo de uniformes escolares',
    'body' => '<img src="/sites/default/files/hogar-images/donativo-uniformes.jpg" alt="Donativo de uniformes escolares" style="width:100%;max-height:380px;object-fit:cover;border-radius:10px;margin-bottom:1.2rem;box-shadow:0 4px 18px rgba(90,42,106,.18);">
<p>En abril de 2025, la empresa <strong>CorpAyuda S.A.</strong> donó 30 uniformes escolares completos para nuestras niñas.</p>
<p>Este tipo de apoyos nos permiten garantizar que cada niña ingrese al año escolar con todo lo necesario. La directora del hogar expresó su gratitud: <em>"Cada uniforme representa una niña que puede ir a la escuela con dignidad."</em></p>
<p>¿Quieres hacer una donación similar? <a href="/registro-benefactor">Regístrate como benefactor</a> y accede a nuestro catálogo de necesidades.</p>',
  ],
  'Taller de pintura: arte que sana' => [
    'img' => 'taller',
    'alt' => 'Niñas participando en taller de pintura',
    'body' => '<img src="/sites/default/files/hogar-images/taller-pintura.jpg" alt="Niñas participando en taller de pintura" style="width:100%;max-height:380px;object-fit:cover;border-radius:10px;margin-bottom:1.2rem;box-shadow:0 4px 18px rgba(90,42,106,.18);">
<p>El pasado marzo inauguramos nuestro nuevo taller de pintura artística, impartido por voluntarios de la comunidad artística local.</p>
<p>15 niñas participan semanalmente en esta actividad que ha mostrado resultados positivos en su desarrollo emocional. La psicóloga del hogar comenta: <em>"El arte les da un lenguaje para expresar lo que las palabras no alcanzan."</em></p>
<p>Si eres artista o maestro y deseas ser voluntario, escríbenos a voluntarios@hogarcorazon.org</p>',
  ],
];

foreach ($noticias as $titulo => $data) {
  $nodes = \Drupal::entityTypeManager()->getStorage('node')
    ->loadByProperties(['type' => 'article', 'title' => $titulo]);
  if ($node = reset($nodes)) {
    $node->body->value  = $data['body'];
    $node->body->format = 'full_html';
    $node->save();
    echo "  ✓ $titulo\n";
  }
}

echo "\n✓ Imágenes integradas en el contenido.\n";
echo "  Acceso: http://localhost:8080/sites/default/files/hogar-images/\n";
