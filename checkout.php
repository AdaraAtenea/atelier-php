<?php
  require_once 'config.php';
  $pageTitle='Finalizar compra';
  if (!is_logged_in()) { 
    header('Location: login.php?next=checkout'); 
    exit; 
  }
  $cart=$_SESSION['cart']??[];
  if (!$cart) { 
    header('Location: carrito.php'); 
    exit; 
  }
  $items=[]; 
  $subtotal=0;
  $ids=array_unique(array_map(fn($key)=>(int)explode('_',$key)[0],array_keys($cart)));
  $marks=implode(',',array_fill(0,count($ids),'?'));
  $stmt=db()->prepare("SELECT * FROM productos WHERE id IN ($marks) AND activo=1"); 
  $stmt->execute($ids);
  $byId=[]; 
  foreach($stmt->fetchAll() as $p)$byId[(int)$p['id']]=$p;
  foreach($cart as $key=>$qty){
    [$pid,$size]=array_pad(explode('_',$key,2),2,''); 
    if(!isset($byId[(int)$pid]))continue; 
    $p=$byId[(int)$pid]; 
    if((int)$p['stock']<(int)$qty){
      $error='No hay existencias suficientes para '.$p['nombre'].'.';
      continue;
    } 
    $line=(float)$p['precio']*(int)$qty;
    $subtotal+=$line;
    
    // CORREGIDO: Usamos 'producto' y 'talla' en singular para coincidir abajo
    $items[]=['producto'=>$p,'talla'=>$size,'qty'=>(int)$qty,'line'=>$line];
  }
  if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $nombre=trim((string)($_POST['nombrecomp']??'')); 
    $telefono=trim((string)($_POST['telefono']??'')); 
    $direccion=trim((string)($_POST['direcion']??'')); 
    $ciudad=trim((string)($_POST['ciudad']??'')); 
    $CP=trim((string)($_POST['CP']??'')); 
    $notas=trim((string)($_POST['notas']??''));
    if (!$nombre || !$telefono || !$direccion || !$ciudad || !$CP) $error='Completa todos los campos obligatorios.';
    elseif (!$items) $error='Tu bolsa no contiene productos disponibles.';
    else {
      try {
        $pdo=db(); $pdo->beginTransaction();
        $orderStmt=$pdo->prepare("INSERT INTO ventas (usuario_id,nombrecomp,telefono,direcion,ciudad,CP,notas,subtotal,total,estado) VALUES (?,?,?,?,?,?,?,?,?,'pendiente')");
        $orderStmt->execute([$_SESSION['usuario']['id'],$nombre,$telefono,$direccion,$ciudad,$CP,$notas,$subtotal,$subtotal]);
        $orderId=(int)$pdo->lastInsertId();
        $itemStmt=$pdo->prepare("INSERT INTO detalles_ventas (venta_id,producto_id,nom_producto,talla,cantidad,precio_unidad,line_total) VALUES (?,?,?,?,?,?,?)");
        $stockStmt=$pdo->prepare("UPDATE productos SET stock=stock-? WHERE id=? AND stock>=?");
        foreach($items as $it){
          $p=$it['producto'];
          $stockStmt->execute([$it['qty'],$p['id'],$it['qty']]);
          if($stockStmt->rowCount()!==1)throw new RuntimeException('El inventario cambió mientras se procesaba el pedido.');
          $itemStmt->execute([$orderId,$p['id'],$p['nombre'],$it['talla'],$it['qty'],$p['precio'],$it['line']]);
        }
        $pdo->commit(); 
        $_SESSION['cart']=[]; header('Location: pedido-confirmado.php?id='.$orderId); 
        exit;
      }
      catch(Throwable $ex) { 
        if(db()->inTransaction()) db()->rollBack(); 
        $error='No se pudo registrar el pedido. Revisa las existencias e inténtalo nuevamente.'; 
      }
    }
  }
require 'includes/header.php';
?>
  <section class="page-banner compact">
    <p class="eyebrow">ATELIER / CHECKOUT</p>
    <h1>Finalizar compra</h1>
    <p>Completa los datos para registrar tu pedido.</p>
  </section>
  <section class="section checkout-section">
    <?php if(!empty($error)): ?>
      <p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
    <div class="checkout-layout">
      <form class="checkout-form" method="post">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <h2>Datos de envío</h2>
        <label>Nombre completo<input name="nombrecomp" required value="<?= e($_POST['nombrecomp']??($_SESSION['usuario']['nombre']??'')) ?>"></label>
        <div class="form-row">
          <label>Teléfono<input name="telefono" required value="<?= e($_POST['telefono']??'') ?>"></label>
          <label>Código postal<input name="CP" required maxlength="10" value="<?= e($_POST['CP']??'') ?>"></label>
        </div>
        <label>Dirección completa<input name="direcion" required value="<?= e($_POST['direcion']??'') ?>"></label>
        <label>Ciudad y estado<input name="ciudad" required value="<?= e($_POST['ciudad']??'') ?>"></label>
        <label>Indicaciones adicionales<textarea name="notas" rows="3"><?= e($_POST['notas']??'') ?></textarea></label>
        <div class="payment-note">
          <strong>Pago</strong>
          <p>Este prototipo registra el pedido sin cobrar. La pasarela de pago se integra por separado.</p>
        </div>
        <button class="button button-dark button-wide" type="submit">Confirmar pedido</button>
      </form>
      <aside class="order-summary">
        <h2>Tu pedido</h2>
        <?php foreach($items as $it): ?>
          <div class="checkout-line">
            <!-- CORREGIDO: Usamos $it['talla'] -->
            <span><?= e($it['producto']['nombre']) ?> · <?= e($it['talla']) ?> × <?= $it['qty'] ?></span>
            <strong><?= money($it['line']) ?></strong>
          </div>
        <?php endforeach; ?>
        <div class="summary-total">
          <span>Total</span>
          <strong><?= money($subtotal) ?></strong>
        </div>
      </aside>
    </div>
  </section>
<?php require 'includes/footer.php'; ?>