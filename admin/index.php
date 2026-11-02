<?php
    require_once '../config.php'; 
    require_admin();
    $pageTitle='Administración';
    $productos=(int)db()->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    $ordenes=(int)db()->query("SELECT COUNT(*) FROM ventas")->fetchColumn();
    $pendientes=(int)db()->query("SELECT COUNT(*) FROM ventas WHERE estado='pendiente'")->fetchColumn();
    require '../includes/header.php';
?>
<section class="page-banner compact">
    <p class="eyebrow">PANEL ADMINISTRATIVO</p>
    <h1>Administración</h1>
    <p>Gestiona la tienda desde un solo lugar.</p>
</section>
<section class="section admin-section">
    <div class="admin-stats">
        <div>
            <span>Productos</span>
            <strong><?= $productos ?></strong>
        </div>
        <div>
            <span>Pedidos</span>
            <strong><?= $ordenes ?></strong>
        </div>
        <div>
            <span>Pendientes</span>
            <strong><?= $pendientes ?></strong>
        </div>
    </div>
    <div class="admin-links">
        <a href="/atelier-php/admin/productos.php">
            <span>01</span>
            <div>
                <h2>Productos e inventario</h2>
                <p>Crear, editar, activar y ajustar existencias.</p>
            </div>
            <b>↗</b>
        </a>
        <a href="/atelier-php/admin/pedidos.php">
            <span>02</span>
            <div>
                <h2>Pedidos</h2>
                <p>Consultar compras y actualizar su estado.</p>
            </div>
            <b>↗</b>
        </a>
    </div>
    <p class="muted">Sesión administrativa: <?= e($_SESSION['usuario']['email']) ?></p>
</section>
<?php require '../includes/footer.php'; ?>
