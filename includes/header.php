<?php
  $pageTitle = $pageTitle ?? 'Moda contemporánea';
  $cartCount = array_sum(array_map('intval', $_SESSION['cart'] ?? []));
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | ATELIER</title>
  <meta name="description" content="ATELIER: básicos contemporáneos, siluetas cuidadas y estilo atemporal.">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">

  <!-- Rutas corregidas -->
  <link rel="stylesheet" href="/atelier-php/css/styles.css">
  <script defer src="/atelier-php/js/app.js"></script>
</head>
<body>

<header class="site-header">
  <a class="wordmark" href="/atelier-php/index.php" aria-label="ATELIER inicio">ATELIER<span>.</span></a>

  <button class="menu-toggle" aria-label="Abrir menú" aria-expanded="false">☰</button>

  <nav class="main-nav">
    <a href="/atelier-php/catalogo.php?novedades=1">Novedades</a>
    <a href="/atelier-php/catalogo.php">Colección</a>
    <a href="/atelier-php/catalogo.php?categoria=1">Mujer</a>
    <a href="/atelier-php/catalogo.php?categoria=2">Hombre</a>
  </nav>

  <div class="header-actions">
    <a href="/atelier-php/catalogo.php" aria-label="Buscar">Buscar</a>

    <?php if (is_logged_in()): ?>
      <a href="/atelier-php/cuenta.php">Mi cuenta</a>
    <?php else: ?>
      <a href="/atelier-php/login.php">Ingresar</a>
    <?php endif; ?>

    <a href="/atelier-php/carrito.php">Bolsa (<?= (int)$cartCount ?>)</a>
  </div>
</header>

<main>
