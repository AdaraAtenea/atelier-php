<?php
    require_once '../config.php';
    require_admin();
    $pageTitle = 'Productos';
    // Procesar formularios
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $action = $_POST['action'] ?? '';
        if ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            $s = db()->prepare("UPDATE productos SET activo = 1 - activo WHERE id = ?");
            $s->execute([$id]);
        }
        if ($action === 'stock') {
            $id = (int)($_POST['id'] ?? 0);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
            if ($stock !== false && $stock !== null && $stock >= 0) {
                $s = db()->prepare("UPDATE productos SET stock = ? WHERE id = ?");
                $s->execute([$stock, $id]);
            }
        }
        // Redirigir únicamente después de procesar un formulario
        header('Location: /atelier-php/admin/productos.php');
        exit;
    }
    // Consultar productos
    $products = db()->query("SELECT p.*,c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON c.id = p.categoria_id ORDER BY p.id DESC")->fetchAll();
    // Mostrar encabezado
    require '../includes/header.php';
?>
<section class="page-banner compact">
    <p class="eyebrow">ADMINISTRACIÓN</p>
    <h1>Productos e inventario</h1>
    <p>Revisa existencias y visibilidad de las piezas.</p>
</section>

<section class="section admin-section">
    <p class="admin-note">
        Para añadir productos nuevos, utiliza phpMyAdmin o amplía el formulario de administración. Esta primera versión permite ajustar existencias y activar/desactivar productos.
    </p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Existencias</th>
                    <th>Visibilidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= e($p['nombre']) ?></td>
                    <td>
                        <?= e($p['categoria_nombre'] ?? '—') ?>
                    </td>
                    <td><?= money($p['precio']) ?></td>
                    <td>
                        <form class="admin-stock-form" method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>" >
                            <input type="hidden" name="action" value="stock">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>" >
                            <input type="number" min="0" name="stock" value="<?= (int)$p['stock'] ?>">
                            <button class="mini-button">Guardar</button>
                        </form>
                    </td>
                    <td>
                        <?= $p['activo'] ? 'Activo' : 'Oculto' ?>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden"name="csrf"value="<?= e(csrf_token()) ?>" >
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>" >
                            <button class="text-link">
                                <?= $p['activo'] ? 'Ocultar' : 'Activar' ?>
                            </button>
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