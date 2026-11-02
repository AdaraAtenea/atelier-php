
<?php
    require_once '../config.php';
    require_admin();
    $pageTitle = 'Pedidos';
    $valid = ['pendiente','confirmado','enviado','completado','cancelado'];
    // Procesar actualización de pedidos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $id = (int)($_POST['id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');
        if ($id > 0 && in_array($status, $valid, true)) {
            $s = db()->prepare("UPDATE ventas SET estado = ? WHERE id = ?");
            $s->execute([$status, $id]);
            }
        header('Location: /atelier-php/admin/pedidos.php');
        exit;
    }
    // Consultar pedidos
    $orders = db()->query("SELECT o.*, u.email FROM ventas o JOIN usuarios u ON u.id = o.usuario_id ORDER BY o.creacion DESC")->fetchAll();
    require '../includes/header.php';
?>
<section class="page-banner compact">
    <p class="eyebrow">ADMINISTRACIÓN</p>
    <h1>Pedidos</h1>
    <p>Consulta y actualiza el estado de los pedidos.</p>
</section>
<section class="section admin-section">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Actualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td>#<?= (int)$o['id'] ?></td>
                    <td>
                        <?= e($o['nombrecomp']) ?>
                        <br>
                        <small><?= e($o['email']) ?></small>
                    </td>
                    <td>
                        <?= e(date('d/m/Y H:i', strtotime($o['creacion']))) ?>
                    </td>
                    <td><?= money($o['total']) ?></td>
                    <td><?= e($o['estado']) ?></td>
                    <td>
                        <form method="post" class="status-form">
                            <input type="hidden"name="csrf"value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
                            <select name="status">
                                <?php foreach ($valid as $v): ?>
                                    <option value="<?= e($v) ?>" <?= $o['estado'] === $v ? 'selected' : '' ?> >
                                    <?= e($v) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="mini-button">Guardar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a class="text-link" href="/atelier-php/admin/index.php">
        ← Volver al panel
    </a>
</section>
<?php require '../includes/footer.php'; ?>