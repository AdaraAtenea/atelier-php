<?php
  require_once 'config.php';
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $stmt = db()->prepare("SELECT p.*, c.nombre AS categoria_name FROM productos p LEFT JOIN categorias c ON c.id=p.categoria_id WHERE p.id=? AND p.activo=1");
  $stmt->execute([$id ?: 0]); 
  $product = $stmt->fetch();
  if (!$product) { 
      http_response_code(404); 
      $pageTitle='Producto no encontrado'; 
      require 'includes/header.php'; 
      echo '<section class="empty-state"><h1>Producto no encontrado</h1><a href="catalogo.php">Volver a la colección</a></section>'; 
      require 'includes/footer.php'; exit; 
    }
  $pageTitle = $product['nombre'];
  $success = '';
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $size = trim((string)($_POST['tallas'] ?? ''));
    $qty = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
    $allowed = array_filter(array_map('trim', explode(',', $product['tallas'] ?? '')));
    if (!in_array($size, $allowed, true)) $error = 'Selecciona una talla válida.';
    elseif (!$qty || $qty < 1 || $qty > 10) $error = 'La cantidad debe estar entre 1 y 10.';
    elseif ((int)$product['stock'] < $qty) $error = 'No hay existencias suficientes para esa cantidad.';
    else {
      $_SESSION['cart'] = $_SESSION['cart'] ?? [];
      $key = $product['id'].'_'.$size;
      $_SESSION['cart'][$key] = min(10, (int)($_SESSION['cart'][$key] ?? 0) + $qty);
      header('Location: carrito.php?agregado=1'); exit;
    }
  }
  require 'includes/header.php';
?>
<section class="product-detail section">
  <div class="detail-image" style="background-image:url('<?= e($product['img_url']) ?>')"></div>
  <div class="detail-copy">
    <p class="eyebrow"><?= e($product['categoria_name'] ?? 'ATELIER') ?> / REF. <?= str_pad((string)$product['id'], 4, '0', STR_PAD_LEFT) ?></p>
    <h1><?= e($product['nombre']) ?></h1>
    <p class="detail-price"><?= money($product['precio']) ?></p>
    <p class="detail-description"><?= nl2br(e($product['descripcion'])) ?></p>
    <?php if (!empty($error)): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="product-form">
      <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
      <label for="size">Talla</label>
      <div class="size-options">
        <?php foreach (array_filter(array_map('trim', explode(',', $product['tallas'] ?? ''))) as $size): ?>
          <label>
            <input type="radio" name="tallas" value="<?= e($size) ?>" required>
            <span><?= e($size) ?></span>
          </label>
        <?php endforeach; ?>
      </div>
      <div class="stock-note">
        <?= (int)$product['stock'] > 0 ? 'Disponible · '.$product['stock'].' en existencia' : 'Agotado' ?>
      </div>
      <label for="quantity">Cantidad</label>
      <input class="quantity-input" type="number" id="quantity" name="cantidad" min="1" max="10" value="1" required>
      <button class="button button-dark button-wide" type="submit" <?= (int)$product['stock']<1?'disabled':'' ?>>Añadir a la bolsa</button>
    </form>
    <div class="detail-notes">
      <p>Envíos a todo México.</p>
      <p>Consulta disponibilidad antes de confirmar tu pedido.</p>
    </div>
  </div>
</section>
<?php require 'includes/footer.php'; ?>
