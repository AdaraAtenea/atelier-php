<?php
    require_once 'config.php';
    $pageTitle='Pedido registrado';
    $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)?:0;
    $stmt=db()->prepare("SELECT id, total, estado, creacion FROM ventas WHERE id=? AND usuario_id=?");
    $stmt->execute([$id,$_SESSION['usuario']['id']??0]); 
    $order=$stmt->fetch();
    require 'includes/header.php';
?>
<section class="confirmation">
    <p class="eyebrow">ATELIER / CONFIRMACIÓN</p>
    <?php if($order): ?>
        <div class="confirmation-mark">✓</div>
        <h1>Gracias por tu pedido.</h1>
        <p>Tu número de pedido es <strong>#<?= (int)$order['id'] ?></strong>.</p>
        <p>Importe registrado: <strong><?= money($order['total']) ?></strong></p>
        <p class="muted">Estado: pendiente de confirmación. Este prototipo no procesa pagos reales.</p>
        <a class="button button-dark" href="cuenta.php">Ver mis pedidos</a>
    <?php else: ?>
        <h1>No encontramos ese pedido.</h1>
        <a href="index.php">Volver al inicio</a>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
