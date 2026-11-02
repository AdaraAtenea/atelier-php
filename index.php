<?php
  require_once 'config.php';
  $pageTitle = 'Moda esencial';
  $featured = db()->query("SELECT p.*, c.nombre AS categoria_name FROM productos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.activo=1 ORDER BY p.creacion DESC LIMIT 4")->fetchAll();
  require 'includes/header.php';
?>
<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow">COLECCIÓN  /  2026</p>
    <h1>La elegancia<br><em>de lo esencial.</em></h1>
    <p class="hero-description">Prendas contemporáneas, líneas limpias y piezas que encuentran su lugar temporada tras temporada.</p>
    <a class="button button-dark" href="catalogo.php">Descubrir colección <span>↗</span></a>
  </div>
  <div class="hero-image" role="img" aria-label="Editorial de moda en tonos neutros">
    <div class="hero-image-label"><span>ATELIER</span><span>01 — 26</span></div>
  </div>
  <div class="hero-index"><span>01</span><span class="index-line"></span><span>03</span></div>
</section>

<section class="intro-strip" id="historia">
  <span class="eyebrow">NUESTRA FILOSOFÍA</span>
  <p>Un armario pensado con intención.<br><em>Calidad, textura y simplicidad.</em></p>
  <span class="intro-mark">A.</span>
</section>

<section class="section" id="novedades">
  <div class="section-heading">
    <div>
      <p class="eyebrow">SELECCIÓN DEL ESTUDIO</p>
      <h2>Piezas destacadas</h2>
    </div>
    <a class="text-link" href="catalogo.php">Ver toda la colección ↗</a>
  </div>
  <div class="product-grid">
    <?php foreach ($featured as $p): ?>
      <a class="product-card" href="producto.php?id=<?= (int)$p['id'] ?>">
        <div class="product-image" style="background-image:url('<?= e($p['img_url']) ?>')">
          <?php if ($p['nuevo']): ?><span class="product-tag">NUEVO</span><?php endif; ?>
        </div>
        <div class="product-info">
          <div>
            <span><?= e($p['categoria_name'] ?? 'Colección') ?></span>
            <h3><?= e($p['nombre']) ?></h3>
          </div>
          <strong><?= money($p['precio']) ?></strong>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="campaign">
  <div class="campaign-image"></div>
  <div class="campaign-copy">
    <p class="eyebrow">EL ARTE DE VESTIR</p>
    <h2>Estilo que<br><em>permanece.</em></h2>
    <p>Descubre una selección de siluetas versátiles y tonos que combinan con todo.</p>
    <a class="button button-outline" href="catalogo.php">Explorar piezas</a></div>
</section>
<?php require 'includes/footer.php'; ?>
