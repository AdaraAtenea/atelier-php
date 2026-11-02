<?php
    require_once 'config.php';
    if(!is_logged_in()){
        header('Location: login.php');
        exit;
    }
    $pageTitle='Mi cuenta';
    
    // CORREGIDO: Seleccionamos 'estado' en lugar de 'status'
    $stmt=db()->prepare("SELECT id,total,estado,creacion FROM ventas WHERE usuario_id=? ORDER BY creacion DESC");
    $stmt->execute([$_SESSION['usuario']['id']]);
    $orders=$stmt->fetchAll();
    require 'includes/header.php';
?>
<section class="page-banner compact">
    <p class="eyebrow">ATELIER / PERFIL</p>
    <h1>Hola, <?= e($_SESSION['usuario']['nombre']) ?>.</h1>
    <p>Tu espacio personal.</p>
</section>
<section class="section account-section">
    <div class="account-head">
        <div>
            <p class="eyebrow">TU ACTIVIDAD</p>
            <h2>Mis pedidos</h2>
        </div>
        <a class="text-link" href="logout.php">Cerrar sesión ↗</a>
    </div>
    <?php if(!$orders): ?>
    <div class="empty-state">
        <h2>Aún no tienes pedidos.</h2>
        <p>Cuando realices una compra, aparecerá aquí.</p>
        <a class="button button-dark" href="catalogo.php">Explorar colección</a>
    </div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td>#<?= (int)$o['id'] ?></td>
                    <td><?= e(date('d/m/Y',strtotime($o['creacion']))) ?></td>
                    <!-- CORREGIDO: Usamos $o['estado'] -->
                    <td><span class="status"><?= e($o['estado']) ?></span></td>
                    <td><?= money($o['total']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>