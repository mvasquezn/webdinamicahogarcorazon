<?php
/**
 * Updates public pages with images and richer HTML.
 * Run with: drush php:script setup/10_update_pages_images.php
 */

use Drupal\node\Entity\Node;

$IMG = '/sites/default/files/hogar-images';

function update_page(string $alias, string $body): void {
  $path = \Drupal::service('path_alias.manager')->getPathByAlias($alias);
  if (!preg_match('|node/(\d+)|', $path, $m)) {
    echo "  ✗ No encontrada: $alias\n";
    return;
  }
  $node = Node::load($m[1]);
  if (!$node) { echo "  ✗ Nodo no cargado: $alias\n"; return; }
  $node->set('body', ['value' => $body, 'format' => 'full_html']);
  $node->save();
  echo "  ✓ $alias\n";
}

echo "── Actualizando páginas con imágenes ──\n";

// ── INICIO ────────────────────────────────────────────────────────────────────
update_page('/inicio', <<<HTML
<div style="margin:-1rem 0 2rem;border-radius:10px;overflow:hidden;max-height:420px;">
  <img src="$IMG/hero.jpg" alt="El Hogar de Corazón" style="width:100%;object-fit:cover;display:block;">
</div>

<h2 style="font-size:1.8rem;margin-bottom:.5rem;">Transformando vidas, construyendo futuros</h2>
<p style="font-size:1.1rem;color:#555;margin-bottom:2rem;">
  Somos una institución de asistencia social dedicada a brindar protección, educación y amor
  a niñas y adolescentes rescatadas de la calle. Actualmente contamos con <strong>casi 80 niñas</strong>
  en nuestras instalaciones. Tu ayuda hace la diferencia.
</p>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:2rem;">
  <div style="text-align:center;">
    <img src="$IMG/educacion.jpg" alt="Educación" style="width:100%;border-radius:8px;height:150px;object-fit:cover;">
    <h3 style="margin:.75rem 0 .25rem;">Educación</h3>
    <p style="color:#666;font-size:.95rem;">Apoyo escolar desde primaria hasta preparatoria.</p>
  </div>
  <div style="text-align:center;">
    <img src="$IMG/salud.jpg" alt="Salud" style="width:100%;border-radius:8px;height:150px;object-fit:cover;">
    <h3 style="margin:.75rem 0 .25rem;">Salud integral</h3>
    <p style="color:#666;font-size:.95rem;">Atención médica, psicológica y odontológica.</p>
  </div>
  <div style="text-align:center;">
    <img src="$IMG/arte.jpg" alt="Arte" style="width:100%;border-radius:8px;height:150px;object-fit:cover;">
    <h3 style="margin:.75rem 0 .25rem;">Arte y cultura</h3>
    <p style="color:#666;font-size:.95rem;">Talleres creativos para el desarrollo emocional.</p>
  </div>
</div>

<div style="background:#f5f5f0;border-radius:10px;padding:1.5rem;text-align:center;">
  <p style="font-size:1.1rem;margin-bottom:1rem;">¿Quieres ser parte del cambio?</p>
  <a href="/como-ayudar" style="background:#6b3fa0;color:white;padding:.75rem 1.5rem;border-radius:6px;text-decoration:none;margin-right:1rem;">¡Quiero ayudar!</a>
  <a href="/registro-benefactor" style="background:white;color:#6b3fa0;padding:.75rem 1.5rem;border-radius:6px;text-decoration:none;border:2px solid #6b3fa0;">Ser benefactor</a>
</div>
HTML);

// ── QUIÉNES SOMOS ─────────────────────────────────────────────────────────────
update_page('/quienes-somos', <<<HTML
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:center;margin-bottom:2.5rem;">
  <div>
    <h2>Nuestra historia</h2>
    <p>El Hogar de Corazón nació hace más de 20 años con una sola misión: dar un hogar seguro
    y digno a niñas y adolescentes en situación de vulnerabilidad.</p>
    <p>Hoy somos un referente en atención especializada, con un equipo multidisciplinario
    dedicado a la protección, educación y desarrollo integral de cada niña.</p>
  </div>
  <img src="$IMG/quienes-somos.jpg" alt="Quiénes somos" style="width:100%;border-radius:10px;object-fit:cover;max-height:320px;">
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
  <div style="background:#f0f7f4;border-radius:8px;padding:1.25rem;">
    <h3 style="color:#0f6e56;">🎯 Misión</h3>
    <p>Brindar protección integral, educación y desarrollo humano a niñas y adolescentes
    rescatadas de la calle, fomentando su reintegración social.</p>
  </div>
  <div style="background:#f0f0f8;border-radius:8px;padding:1.25rem;">
    <h3 style="color:#3c3489;">🔭 Visión</h3>
    <p>Ser el referente nacional en atención especializada para niñas y adolescentes en riesgo,
    con un modelo replicable y sostenible.</p>
  </div>
</div>

<h3>Nuestros valores</h3>
<div style="display:flex;flex-wrap:wrap;gap:.75rem;">
  <span style="background:#e8f5e9;color:#2e7d32;padding:.5rem 1rem;border-radius:20px;">💚 Amor incondicional</span>
  <span style="background:#e8eaf6;color:#283593;padding:.5rem 1rem;border-radius:20px;">🤝 Respeto y dignidad</span>
  <span style="background:#fce4ec;color:#880e4f;padding:.5rem 1rem;border-radius:20px;">❤️ Compromiso social</span>
  <span style="background:#fff8e1;color:#f57f17;padding:.5rem 1rem;border-radius:20px;">⭐ Transparencia</span>
</div>
HTML);

// ── PROGRAMAS ─────────────────────────────────────────────────────────────────
update_page('/programas', <<<HTML
<h2>Nuestros programas</h2>
<p style="color:#555;margin-bottom:2rem;">Cuatro pilares que guían el desarrollo integral de cada niña.</p>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/educacion.jpg" alt="Educación" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">🎓 Educación</h3>
      <p>Apoyo escolar completo desde educación básica hasta preparatoria, con tutorías
      individualizadas y seguimiento continuo de cada alumna.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/salud.jpg" alt="Salud" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">🏥 Salud integral</h3>
      <p>Atención médica, psicológica y odontológica permanente dentro del hogar,
      con especialistas que visitan semanalmente.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/arte.jpg" alt="Arte y cultura" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">🎨 Arte y cultura</h3>
      <p>Talleres de pintura, música, danza y teatro para el desarrollo creativo
      y emocional de las niñas.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/vocacional.jpg" alt="Capacitación laboral" style="width:100%;height:200px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">💼 Capacitación laboral</h3>
      <p>Formación en habilidades para la vida y orientación vocacional para las
      adolescentes que se preparan para su reintegración.</p>
    </div>
  </div>
</div>
HTML);

// ── HISTORIAS ─────────────────────────────────────────────────────────────────
update_page('/historias', <<<HTML
<h2>Historias que inspiran</h2>
<p style="color:#555;margin-bottom:2rem;">Cada niña trae consigo una historia de resiliencia.
Aquí compartimos algunas (con nombres cambiados para proteger su identidad).</p>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:2rem;align-items:start;background:#f9f9f6;border-radius:10px;overflow:hidden;margin-bottom:2rem;">
  <img src="$IMG/testimonio-lucia.jpg" alt="Historia de Lucía" style="width:100%;height:100%;object-fit:cover;min-height:220px;">
  <div style="padding:1.5rem;">
    <h3 style="margin-top:0;">Una nueva oportunidad para Lucía</h3>
    <p><em>"Cuando llegué al hogar tenía miedo de todo. Hoy me gradué de secundaria y tengo sueños."</em></p>
    <p>Lucía llegó en 2018 con apenas 10 años. Gracias a los programas de atención psicológica
    y educativa, logró superar sus traumas y concluir su educación secundaria. Hoy prepara
    su ingreso a la preparatoria.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/donativo-uniformes.jpg" alt="Donativo de uniformes" style="width:100%;height:180px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">30 niñas con nuevo uniforme</h3>
      <p>En abril de 2025, la empresa CorpAyuda donó 30 uniformes escolares completos.
      Gracias a la red de benefactores, cada niña inició el ciclo con todo lo necesario.</p>
    </div>
  </div>

  <div style="border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);">
    <img src="$IMG/taller-pintura.jpg" alt="Taller de pintura" style="width:100%;height:180px;object-fit:cover;">
    <div style="padding:1.25rem;">
      <h3 style="margin-top:0;">Arte que sana</h3>
      <p>15 niñas participan semanalmente en nuestro taller de pintura artística.
      La expresión creativa ha mostrado resultados positivos en su desarrollo emocional.</p>
    </div>
  </div>
</div>
HTML);

// ── TRANSPARENCIA ─────────────────────────────────────────────────────────────
update_page('/transparencia', <<<HTML
<img src="$IMG/transparencia.jpg" alt="Transparencia" style="width:100%;border-radius:10px;max-height:300px;object-fit:cover;margin-bottom:2rem;">

<h2>Transparencia y rendición de cuentas</h2>
<p>Nos comprometemos a la total transparencia en el manejo de los recursos que la sociedad nos confía.</p>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin:2rem 0;text-align:center;">
  <div style="background:#e8f5e9;border-radius:10px;padding:1.5rem;">
    <div style="font-size:2.5rem;font-weight:700;color:#2e7d32;">78</div>
    <div style="color:#555;">Niñas atendidas en 2024</div>
  </div>
  <div style="background:#e3f2fd;border-radius:10px;padding:1.5rem;">
    <div style="font-size:2.5rem;font-weight:700;color:#1565c0;">1,240</div>
    <div style="color:#555;">Consultas médicas realizadas</div>
  </div>
  <div style="background:#fce4ec;border-radius:10px;padding:1.5rem;">
    <div style="font-size:2.5rem;font-weight:700;color:#880e4f;">320</div>
    <div style="color:#555;">Kits escolares entregados</div>
  </div>
</div>

<h3>¿Cómo se usan los donativos?</h3>
<div style="background:#f9f9f6;border-radius:8px;padding:1.25rem;">
  <div style="display:flex;align-items:center;margin-bottom:.75rem;">
    <div style="background:#4caf50;height:20px;border-radius:4px;flex:60;margin-right:.75rem;"></div>
    <span>60% — Alimentación y salud</span>
  </div>
  <div style="display:flex;align-items:center;margin-bottom:.75rem;">
    <div style="background:#2196f3;height:20px;border-radius:4px;flex:25;margin-right:.75rem;"></div>
    <span>25% — Educación y materiales</span>
  </div>
  <div style="display:flex;align-items:center;margin-bottom:.75rem;">
    <div style="background:#ff9800;height:20px;border-radius:4px;flex:10;margin-right:.75rem;"></div>
    <span>10% — Mantenimiento</span>
  </div>
  <div style="display:flex;align-items:center;">
    <div style="background:#9e9e9e;height:20px;border-radius:4px;flex:5;margin-right:.75rem;"></div>
    <span>5% — Administración</span>
  </div>
</div>
HTML);

// ── CÓMO AYUDAR ───────────────────────────────────────────────────────────────
update_page('/como-ayudar', <<<HTML
<img src="$IMG/como-ayudar.jpg" alt="Cómo ayudar" style="width:100%;border-radius:10px;max-height:300px;object-fit:cover;margin-bottom:2rem;">

<h2>¡Tu apoyo transforma vidas!</h2>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
  <div style="border:2px solid #e0e0e0;border-radius:10px;padding:1.25rem;">
    <h3 style="margin-top:0;">📦 Donar en especie</h3>
    <p>Explora el catálogo de necesidades específicas de nuestras niñas: ropa, libros, medicinas,
    materiales escolares y más.</p>
    <a href="/registro-benefactor" style="background:#6b3fa0;color:white;padding:.6rem 1.2rem;border-radius:6px;text-decoration:none;display:inline-block;margin-top:.5rem;">Ver necesidades</a>
  </div>

  <div style="border:2px solid #e0e0e0;border-radius:10px;padding:1.25rem;">
    <h3 style="margin-top:0;">💳 Donar en efectivo</h3>
    <p>Tu aportación monetaria nos permite atender las necesidades más urgentes
    de manera inmediata.</p>
    <p style="background:#f5f5f0;padding:.75rem;border-radius:6px;font-size:.9rem;font-family:monospace;">
      BANCO CORAZÓN<br>Núm. 012-345678-90<br>CLABE: 012000001234567890
    </p>
  </div>

  <div style="border:2px solid #e0e0e0;border-radius:10px;padding:1.25rem;">
    <h3 style="margin-top:0;">🙋 Ser voluntario</h3>
    <p>Si tienes habilidades en educación, salud, arte o administración, ¡te necesitamos!
    Nuestros voluntarios cambian la vida de las niñas cada semana.</p>
    <a href="mailto:voluntarios@hogarcorazon.org" style="color:#6b3fa0;">voluntarios@hogarcorazon.org</a>
  </div>

  <div style="border:2px solid #e0e0e0;border-radius:10px;padding:1.25rem;">
    <h3 style="margin-top:0;">🏢 Convenio empresarial</h3>
    <p>Tu empresa puede convertirse en patrocinadora oficial. Ofrecemos reconocimiento
    público, recibos deducibles de impuestos y reportes de impacto.</p>
    <a href="/contacto" style="background:#6b3fa0;color:white;padding:.6rem 1.2rem;border-radius:6px;text-decoration:none;display:inline-block;margin-top:.5rem;">Contáctanos</a>
  </div>
</div>
HTML);

// ── CONTACTO ─────────────────────────────────────────────────────────────────
update_page('/contacto', <<<HTML
<img src="$IMG/contacto.jpg" alt="Contacto" style="width:100%;border-radius:10px;max-height:280px;object-fit:cover;margin-bottom:2rem;">

<h2>Contáctanos</h2>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
  <div>
    <p><strong>El Hogar de Corazón, A.C.</strong></p>
    <p>📍 Av. de la Esperanza 123, Col. Nueva Vida<br>Ciudad de México, C.P. 06700</p>
    <p>📞 (55) 5555-1234</p>
    <p>✉️ contacto@hogarcorazon.org</p>
    <p>🕐 Lunes a viernes · 9:00 – 17:00 hrs</p>
  </div>
  <div style="background:#f5f5f0;border-radius:10px;padding:1.5rem;">
    <h3 style="margin-top:0;">¿Quieres ser benefactor?</h3>
    <p>Únete a nuestra red de benefactores y apoya directamente las necesidades
    de nuestras niñas.</p>
    <a href="/registro-benefactor" style="background:#6b3fa0;color:white;padding:.75rem 1.5rem;border-radius:6px;text-decoration:none;display:inline-block;">Registrarme como benefactor</a>
  </div>
</div>
HTML);

\Drupal::service('cache.render')->invalidateAll();
echo "\n✓ Páginas actualizadas con imágenes.\n";
