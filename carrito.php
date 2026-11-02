<?php
  require_once 'config.php';
  $pageTitle = 'Tu bolsa';
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    /*verify_csrf();
    $action = $_POST['action'] ?? '';
    $key = (string)($_POST['key'] ?? '');
    if ($action==='remove') unset($_SESSION['cart'][$key]);
    if ($action==='update' && isset($_SESSION['cart'][$key])) {
      $qty = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
      if ($qty && $qty > 0 && $qty <= 10) $_SESSION['cart'][$key] = $qty;
      else unset($_SESSION['cart'][$key]);
    }*/

    
    verify_csrf();

    $action = $_POST['action'] ?? '';
    $key = (string)($_POST['key'] ?? '');

    if ($action === 'remove') {
        unset($_SESSION['cart'][$key]);
    }

    if ($action === 'update' && isset($_SESSION['cart'][$key])) {
        $qty = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);

        if ($qty !== false && $qty !== null && $qty >= 1 && $qty <= 10) {
            $_SESSION['cart'][$key] = $qty;
        }
    }

    header('Location: /atelier-php/carrito.php');
    exit;
}





    
  $cart = $_SESSION['cart'] ?? []; $items=[]; $subtotal=0;
  if ($cart) {
    $ids = array_unique(array_map(fn($key)=>(int)explode('_',$key)[0], array_keys($cart)));
    if ($ids) {
      $marks = implode(',', array_fill(0, count($ids), '?'));
      $stmt=db()->prepare("SELECT * FROM productos WHERE id IN ($marks)"); $stmt->execute($ids);
      $byId=[]; foreach($stmt->fetchAll() as $p) $byId[(int)$p['id']]=$p;
      foreach($cart as $key=>$qty) {
        [$pid,$size]=array_pad(explode('_',$key,2),2,'');
        if (!isset($byId[(int)$pid])) continue;
        $p=$byId[(int)$pid]; $line=(float)$p['precio']*(int)$qty; $subtotal+=$line;
        $items[]=['key'=>$key,'productos'=>$p,'tallas'=>$size,'qty'=>(int)$qty,'line'=>$line];
      }
    }
  }
  require 'includes/header.php';
?>
<section class="page-banner compact">
  <p class="eyebrow">ATELIER / TU SELECCIÓN</p>
  <h1>Tu bolsa</h1><p><?= count($items) ?> artículo(s)</p>
</section>
<section class="section cart-section">
  <?php if (isset($_GET['agregado'])): ?>
    <p class="alert alert-success">La pieza se añadió a tu bolsa.</p>
  <?php endif; ?>
  <?php if (!$items): ?>
    <div class="empty-state">
      <h2>Tu bolsa está vacía.</h2>
      <p>Encuentra piezas que acompañen tu estilo.</p>
      <a class="button button-dark" href="catalogo.php">Explorar colección</a>
    </div>
  <?php else: ?>
  <div class="cart-layout">
    <div class="cart-items">
    <?php foreach($items as $item): $p=$item['productos']; ?>
      <article class="cart-item">
        <div class="cart-thumb" style="background-image:url('<?= e($p['img_url']) ?>')"></div>
        <div class="cart-item-main">
          <a href="producto.php?id=<?= (int)$p['id'] ?>"><h3><?= e($p['nombre']) ?></h3></a>
          <p>Talla: <?= e($item['tallas']) ?></p>
          <p><?= money($p['precio']) ?></p>
          <form method="post" class="inline-form">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="key" value="<?= e($item['key']) ?>">
            <input type="hidden" name="action" value="update">
            <label class="sr-only" for="qty-<?= e($item['key']) ?>">Cantidad</label>
            <input id="qty-<?= e($item['key']) ?>" type="number" name="cantidad" min="1" max="10" value="<?= $item['qty'] ?>">
            <button class="text-link" type="submit">Actualizar</button>
          </form>
          <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="key" value="<?= e($item['key']) ?>">
            <input type="hidden" name="action" value="remove">
            <button class="remove-link" type="submit">Eliminar</button>
          </form>
        </div>
        <strong class="cart-line-total"><?= money($item['line']) ?></strong>
      </article>
<?php endforeach; ?>
</div>
<aside class="order-summary">
  <h2>Resumen</h2>
  <div>
    <span>Subtotal</span>
    <strong><?= money($subtotal) ?></strong>
  </div>
  <div>
    <span>Envío</span>
    <span>Se calcula al confirmar</span>
  </div>
  <p class="summary-note">Los precios se muestran en pesos mexicanos. El pedido se confirmará después de validar tus datos.</p>
  <a class="button button-dark button-wide" href="checkout.php">Continuar compra</a>
  <a class="text-link centered" href="catalogo.php">Seguir explorando</a>
</aside>
</div>
<?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
