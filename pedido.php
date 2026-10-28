<?php
require_once __DIR__ . '/clase5/tienda.php';
// El comprobante pertenece a esta sesión: no se acepta un id desde la URL.
$id = $_SESSION['pedido_confirmado']['id'] ?? 0;
$consulta = $pdo->prepare('SELECT p.codigo, p.nombre_cliente, p.created_at, e.nombre AS estado
    FROM pedidos p INNER JOIN estados_pedido e ON e.id = p.estado_id WHERE p.id = :id');
$consulta->execute(['id' => $id]);
$pedido = $consulta->fetch();
if (!$pedido) {
    header('Location: carrito.php', true, 303);
    exit;
}
$consulta = $pdo->prepare('SELECT pr.nombre, d.talle, d.cantidad, d.precio_unitario
    FROM detalle_pedidos d INNER JOIN productos pr ON pr.id = d.producto_id WHERE d.pedido_id = :id ORDER BY d.id');
$consulta->execute(['id' => $id]);
$detalle = $consulta->fetchAll();
$total = 0;
$titulo_pagina = 'Pedido recibido | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
    <p class="pasos-compra">Carrito → Tus datos → Pedido recibido</p>
    <h1>Recibimos tu pedido</h1>
    <p>Gracias, <?= escapar($pedido['nombre_cliente']) ?>. Guardá este código para consultar tu compra:</p>
    <p class="aviso"><strong><?= escapar($pedido['codigo']) ?></strong></p>
    <p>Estado: <?= escapar($pedido['estado']) ?>. Retiro en tienda y pago al retirar. Tu pedido todavía no está pagado.</p>
    <div class="tabla-info"><table><caption>Detalle de tu pedido</caption>
        <thead><tr><th>Producto</th><th>Talle</th><th>Cantidad</th><th>Subtotal</th></tr></thead><tbody>
        <?php foreach ($detalle as $item): $subtotal = centavos($item['precio_unitario']) * $item['cantidad']; $total += $subtotal; ?>
        <tr><td><?= escapar($item['nombre']) ?></td><td><?= escapar($item['talle']) ?></td><td><?= (int) $item['cantidad'] ?></td><td><?= escapar(precio_ars(importe_decimal($subtotal))) ?></td></tr>
        <?php endforeach; ?>
        </tbody><tfoot><tr><th colspan="3">Total</th><td><strong><?= escapar(precio_ars(importe_decimal($total))) ?></strong></td></tr></tfoot>
    </table></div>
    <p>El pedido quedó registrado. La tienda usará los datos que ingresaste para coordinar su confirmación y retiro.</p>
    <p class="nota-precios">Precios orientativos para esta presentación.</p>
    <a class="boton-tienda" href="clase5/buscar.php">Volver al catálogo</a>
    <?php if (esta_autenticado()): ?><p><a href="mis-pedidos.php">Ver mis pedidos</a></p><?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
