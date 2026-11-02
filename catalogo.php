<?php
  require_once 'config.php';
  $pageTitle = 'Colección';
  $categoryId = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: 0;
  $search = trim((string)($_GET['q'] ?? ''));
  $sort = (string)($_GET['ventas'] ?? 'recent');
  // Detectar si se están consultando las novedades
  $onlyNew = isset($_GET['novedades']) && $_GET['novedades'] === '1';
  $where = ['p.activo = 1'];
  $params = [];
  // Filtrar por novedades
  if ($onlyNew) {
    $where[] = 'p.nuevo = 1';
    $pageTitle = 'Novedades';
  }
  // Filtrar por categoría
  if ($categoryId > 0) {
    $where[] = 'p.categoria_id = ?';
    $params[] = $categoryId;
  }
  // Buscar productos
  if ($search !== '') {
    $where[] = '(p.nombre LIKE ? OR p.descripcion LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
  }
  // Ordenar productos
  $order = match($sort) {
    'price_asc'  => 'p.precio ASC',
    'price_desc' => 'p.precio DESC',
    'nombre'     => 'p.nombre ASC',
    default      => 'p.creacion DESC'
  };
  $sql = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id WHERE " . implode(' AND ', $where) . " ORDER BY $order";
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $products = $stmt->fetchAll();
  $categories = db()->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetchAll();
  require 'includes/header.php';
?>
<section class="page-banner">
  <p class="eyebrow">ATELIER / CATÁLOGO</p>
  <h1><?= $onlyNew ? 'Novedades' : 'La colección' ?></h1>
  <p>
    <?= $onlyNew
        ? 'Descubre las últimas piezas que llegaron a ATELIER.'
        : 'Piezas esenciales para todos los días.' ?>
  </p>
</section>
<section class="section catalog-section">
  <form class="catalog-tools" method="get">
    <div class="search-field">
      <input type="search" name="q" placeholder="Buscar prendas..." value="<?= e($search) ?>">
      <button aria-label="Buscar">⌕</button>
    </div>
    <?php if ($categoryId): ?>
    <input type="hidden" name="categoria" value="<?= $categoryId ?>">
    <?php endif; ?>
    <label class="sort-control">Ordenar
      <select name="ventas" onchange="this.form.submit()">
        <option value="recent" <?= $sort==='recent'?'selected':'' ?>>Más recientes</option>
        <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Precio: menor a mayor</option>
        <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Precio: mayor a menor</option>
        <option value="nombre" <?= $sort==='nombre'?'selected':'' ?>>Nombre</option>
      </select>
    </label>
  </form>
  <div class="catalog-layout">
    <aside class="filters">
      <h3>Categorías</h3>
      <a class="<?= !$categoryId?'activo':'' ?>" href="catalogo.php">Todas las piezas</a>
      <?php foreach ($categories as $c): ?>
      <a class="<?= $categoryId==(int)$c['id']?'activo':'' ?>" href="catalogo.php?categoria=<?= (int)$c['id'] ?>"><?= e($c['nombre']) ?></a>
      <?php endforeach; ?>
    </aside>
    <div class="catalog-results">
      <p class="results-count"><?= count($products) ?> piezas encontradas</p>
      <?php if (!$products): ?>
      <div class="empty-state">
        <h2>No encontramos esa pieza.</h2>
        <p>Prueba otra búsqueda o explora toda la colección.</p>
        <a class="button button-dark" href="catalogo.php">Ver colección</a>
      </div>
      <?php else: ?>
      <div class="product-grid">
        <?php foreach ($products as $p): ?>
        <a class="product-card" href="producto.php?id=<?= (int)$p['id'] ?>">
          <div class="product-image" style="background-image:url('<?= e($p['img_url']) ?>')">
            <?php if ($p['nuevo']): ?>
            <span class="product-tag">NUEVO</span>
            <?php endif; ?>
          </div>
          <div class="product-info">
            <div>
              <span><?= e($p['nombre'] ?? 'Colección') ?></span>
              <h3><?= e($p['nombre']) ?></h3>
            </div>
            <strong><?= money($p['precio']) ?></strong>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
