<?php
require_once __DIR__ . '/auth_guard.php';
verificar_rol('usuario');
$titulo_pagina = 'Mis pedidos | TF Clothing';
$pedidos = [];
$seleccion = null;
$detalle = [];
$error = '';
try {
    $consulta = $pdo->prepare('SELECT p.id, p.codigo, p.created_at, e.nombre AS estado,
        (SELECT SUM(d.cantidad * d.precio_unitario) FROM detalle_pedidos d WHERE d.pedido_id = p.id) AS total
        FROM pedidos p INNER JOIN estados_pedido e ON e.id = p.estado_id
        WHERE p.usuario_id = :usuario_id ORDER BY p.id DESC LIMIT 100');
    $consulta->execute(['usuario_id' => $_SESSION['usuario_id']]);
    $pedidos = $consulta->fetchAll();
    if (isset($_GET['id'])) {
        $id = filter_var(obtener_campo($_GET, 'id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            http_response_code(400);
            $error = 'Elegí un pedido válido desde tu listado.';
        } else {
            $consulta = $pdo->prepare('SELECT p.id, p.codigo, e.nombre AS estado
                FROM pedidos p INNER JOIN estados_pedido e ON e.id = p.estado_id
                WHERE p.id = :id AND p.usuario_id = :usuario_id');
            $consulta->execute(['id' => $id, 'usuario_id' => $_SESSION['usuario_id']]);
            $seleccion = $consulta->fetch();
            if (!$seleccion) {
                http_response_code(404);
                $error = 'No encontramos ese pedido en tu cuenta.';
            } else {
                $consulta = $pdo->prepare('SELECT pr.nombre, d.talle, d.cantidad, d.precio_unitario
                    FROM detalle_pedidos d INNER JOIN productos pr ON pr.id = d.producto_id
                    INNER JOIN pedidos p ON p.id = d.pedido_id
                    WHERE d.pedido_id = :id AND p.usuario_id = :usuario_id ORDER BY d.id');
                $consulta->execute(['id' => $id, 'usuario_id' => $_SESSION['usuario_id']]);
                $detalle = $consulta->fetchAll();
            }
        }
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    $error = 'No pudimos cargar tus pedidos. Intentá nuevamente más tarde.';
}
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
    <h1>Mis pedidos</h1>
    <p>Acá aparecen las compras que confirmaste con tu cuenta iniciada.</p>
    <?php if ($error !== ''): ?><p class="aviso" role="alert"><?= escapar($error) ?></p><?php endif; ?>
    <?php if ($seleccion): $total = 0; ?>
        <h2><?= escapar($seleccion['codigo']) ?></h2>
        <p>Estado: <?= escapar($seleccion['estado']) ?>. Retiro en tienda y pago al retirar.</p>
        <div class="tabla-info"><table><caption>Detalle del pedido</caption>
            <thead><tr><th>Producto</th><th>Talle</th><th>Cantidad</th><th>Subtotal</th></tr></thead><tbody>
            <?php foreach ($detalle as $item): $subtotal = centavos($item['precio_unitario']) * $item['cantidad']; $total += $subtotal; ?>
            <tr><td><?= escapar($item['nombre']) ?></td><td><?= escapar($item['talle']) ?></td><td><?= (int) $item['cantidad'] ?></td><td><?= escapar(precio_ars(importe_decimal($subtotal))) ?></td></tr>
            <?php endforeach; ?></tbody>
            <tfoot><tr><th colspan="3">Total</th><td><strong><?= escapar(precio_ars(importe_decimal($total))) ?></strong></td></tr></tfoot>
        </table></div>
    <?php endif; ?>
    <?php if (!$pedidos): ?>
        <p>Todavía no tenés pedidos realizados con esta cuenta.</p>
        <a class="boton-tienda" href="clase5/buscar.php">Ver la colección</a>
    <?php else: ?>
        <div class="tabla-info"><table><caption>Tus últimos pedidos</caption>
            <thead><tr><th>Código</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Detalle</th></tr></thead><tbody>
            <?php foreach ($pedidos as $pedido): ?><tr>
                <td><?= escapar($pedido['codigo']) ?></td><td><?= escapar($pedido['created_at']) ?></td><td><?= escapar($pedido['estado']) ?></td>
                <td><?= escapar(precio_ars($pedido['total'] ?? '0.00')) ?></td><td><a href="mis-pedidos.php?id=<?= (int) $pedido['id'] ?>">Ver pedido</a></td>
            </tr><?php endforeach; ?></tbody>
        </table></div>
    <?php endif; ?>
    <p><a href="cuenta.php">Volver a mi cuenta</a></p>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
