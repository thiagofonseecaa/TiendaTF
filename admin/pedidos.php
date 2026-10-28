<?php
require_once __DIR__ . '/../clase5/tienda.php';
$base_sitio = '../';
$titulo_pagina = 'Pedidos | TF Clothing';
$consulta = $pdo->prepare('SELECT p.id, p.codigo, p.created_at,
    COALESCE(p.nombre_cliente, CONCAT(u.nombre, " ", u.apellido)) AS cliente,
    COALESCE(p.email_cliente, u.email) AS email, p.telefono_cliente, e.nombre AS estado,
    (SELECT SUM(d.cantidad * d.precio_unitario) FROM detalle_pedidos d WHERE d.pedido_id = p.id) AS total
    FROM pedidos p INNER JOIN estados_pedido e ON e.id = p.estado_id
    LEFT JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id DESC LIMIT :limite');
$consulta->bindValue('limite', 100, PDO::PARAM_INT);
$consulta->execute();
$pedidos = $consulta->fetchAll();
$seleccion = null;
$detalle = [];
if (isset($_GET['id'])) {
    $id = filter_var(obtener_campo($_GET, 'id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    foreach ($pedidos as $fila) if ($id !== false && (int) $fila['id'] === $id) $seleccion = $fila;
    if ($seleccion) {
        $consulta = $pdo->prepare('SELECT pr.nombre, d.talle, d.cantidad, d.precio_unitario
            FROM detalle_pedidos d INNER JOIN productos pr ON pr.id = d.producto_id WHERE d.pedido_id = :id ORDER BY d.id');
        $consulta->execute(['id' => $id]);
        $detalle = $consulta->fetchAll();
    } else http_response_code(404);
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-info gestion-productos">
    <h1>Pedidos recibidos</h1>
    <p>Últimos 100 pedidos. Las compras de la tienda se registran con pago al retirar y quedan pendientes de confirmación.</p>
    <p><a href="index.php">Gestionar productos</a></p>
    <?php if (isset($_GET['id']) && !$seleccion): ?><p class="aviso">No encontramos ese pedido en el listado.</p><?php endif; ?>
    <?php if ($seleccion): ?>
        <h2><?= escapar($seleccion['codigo']) ?></h2>
        <p><?= escapar($seleccion['cliente'] ?? '') ?> · <?= escapar($seleccion['email'] ?? '') ?> · <?= escapar($seleccion['telefono_cliente'] ?? '') ?></p>
        <div class="tabla-info"><table><thead><tr><th>Producto</th><th>Talle</th><th>Cantidad</th><th>Precio por unidad</th></tr></thead><tbody>
        <?php foreach ($detalle as $item): ?><tr><td><?= escapar($item['nombre']) ?></td><td><?= escapar($item['talle']) ?></td><td><?= (int) $item['cantidad'] ?></td><td><?= escapar(precio_ars($item['precio_unitario'])) ?></td></tr><?php endforeach; ?>
        </tbody></table></div><p><strong>Total: <?= escapar(precio_ars($seleccion['total'] ?? '0.00')) ?></strong></p>
    <?php endif; ?>
    <?php if (!$pedidos): ?><p>Todavía no hay pedidos.</p><?php else: ?>
    <div class="tabla-info"><table><caption>Pedidos de la tienda</caption>
        <thead><tr><th>Código</th><th>Fecha</th><th>Cliente</th><th>Estado</th><th>Total</th><th>Detalle</th></tr></thead><tbody>
        <?php foreach ($pedidos as $fila): ?><tr><td><?= escapar($fila['codigo']) ?></td><td><?= escapar($fila['created_at']) ?></td><td><?= escapar($fila['cliente'] ?? '') ?></td><td><?= escapar($fila['estado']) ?></td><td class="precio-tabla"><?= escapar(precio_ars($fila['total'] ?? '0.00')) ?></td><td><a href="pedidos.php?id=<?= (int) $fila['id'] ?>">Ver pedido</a></td></tr><?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
