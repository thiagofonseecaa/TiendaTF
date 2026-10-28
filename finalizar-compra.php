<?php
require_once __DIR__ . '/clase5/tienda.php';
if (esta_autenticado()) {
    require_once __DIR__ . '/auth_guard.php';
    verificar_rol('usuario');
}
$es_post = $_SERVER['REQUEST_METHOD'] === 'POST';
$nombre = $es_post ? obtener_campo($_POST, 'nombre') : ($_SESSION['usuario_nombre'] ?? '');
$email = $es_post ? obtener_campo($_POST, 'email') : ($_SESSION['usuario_email'] ?? '');
$telefono = obtener_campo($_POST, 'telefono');
$errores = [];
$resumen = resumen_carrito();

// Repetir el envío del mismo formulario lleva al comprobante ya creado.
if ($es_post && hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf')) &&
    isset($_SESSION['pedido_confirmado']) &&
    hash_equals($_SESSION['pedido_confirmado']['clave'], obtener_campo($_POST, 'compra'))) {
    header('Location: pedido.php', true, 303);
    exit;
}
if (!$resumen['items']) {
    header('Location: carrito.php', true, 303);
    exit;
}
if (!$es_post) {
    $_SESSION['compra'] ??= ['clave' => bin2hex(random_bytes(32))];
    $_SESSION['compra']['firma'] = firma_carrito($resumen);
}
if ($es_post) {
    if (!hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf')) ||
        !isset($_SESSION['compra']) ||
        !hash_equals($_SESSION['compra']['clave'], obtener_campo($_POST, 'compra'))) {
        http_response_code(403);
        $errores[] = 'El formulario venció. Volvé al carrito y continuá la compra.';
    }
    if ($nombre === '' || mb_strlen($nombre) > 200) $errores[] = 'Ingresá tu nombre y apellido (hasta 200 caracteres).';
    if (mb_strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'Ingresá un correo electrónico válido.';
    if (!preg_match('/^[0-9+() .-]{6,30}$/D', $telefono)) $errores[] = 'Ingresá un teléfono válido de entre 6 y 30 caracteres.';
    if ($errores && http_response_code() !== 403) http_response_code(422);

    if (!$errores) {
        try {
            $pdo->beginTransaction();
            $ids = array_map(fn($item) => (int) $item['producto']['id'], $resumen['items']);
            sort($ids);
            $marcas = implode(',', array_fill(0, count($ids), '?'));
            $bloquear = $pdo->prepare("SELECT id FROM productos WHERE id IN ($marcas) ORDER BY id FOR UPDATE");
            $bloquear->execute($ids);
            $productos = cargar_productos();
            $resumen = resumen_carrito();
            if (!hash_equals($_SESSION['compra']['firma'], firma_carrito($resumen))) {
                $pdo->rollBack();
                http_response_code(409);
                $errores[] = 'Cambió el precio o la disponibilidad de tu selección. Revisá el resumen antes de confirmar otra vez.';
                $_SESSION['compra']['firma'] = firma_carrito($resumen);
            } else {
                $consulta = $pdo->prepare('SELECT id FROM estados_pedido WHERE nombre = :nombre');
                $consulta->execute(['nombre' => 'pendiente']);
                $estado_id = $consulta->fetchColumn();
                if (!$estado_id) throw new RuntimeException('Falta el estado pendiente.');
                $codigo = 'TF-' . strtoupper(substr(hash('sha256', $_SESSION['compra']['clave']), 0, 20));
                $consulta = $pdo->prepare('INSERT INTO pedidos (codigo, usuario_id, estado_id, nombre_cliente, email_cliente, telefono_cliente)
                    VALUES (:codigo, :usuario_id, :estado_id, :nombre, :email, :telefono)');
                $consulta->execute(['codigo' => $codigo, 'usuario_id' => esta_autenticado() ? $_SESSION['usuario_id'] : null, 'estado_id' => $estado_id, 'nombre' => $nombre, 'email' => $email, 'telefono' => $telefono]);
                $pedido_id = (int) $pdo->lastInsertId();
                $consulta = $pdo->prepare('INSERT INTO detalle_pedidos (pedido_id, producto_id, talle, cantidad, precio_unitario)
                    VALUES (:pedido_id, :producto_id, :talle, :cantidad, :precio_unitario)');
                foreach ($resumen['items'] as $item) {
                    $consulta->execute(['pedido_id' => $pedido_id, 'producto_id' => $item['producto']['id'],
                        'talle' => $item['talle'], 'cantidad' => $item['cantidad'], 'precio_unitario' => $item['producto']['precio']]);
                }
                $pdo->commit();
                $_SESSION['pedido_confirmado'] = ['id' => $pedido_id, 'clave' => $_SESSION['compra']['clave']];
                $_SESSION['carrito'] = [];
                unset($_SESSION['compra']);
                header('Location: pedido.php', true, 303);
                exit;
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            error_log($e->getMessage());
            http_response_code(503);
            $errores[] = 'No pudimos guardar el pedido. Tu carrito sigue disponible. Intentá nuevamente.';
        }
    }
}
$titulo_pagina = 'Confirmar compra | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-catalogo">
    <p class="pasos-compra">Carrito → Tus datos → Pedido recibido</p>
    <h1>Confirmar compra</h1>
    <p>Completá tus datos y revisá los productos de tu pedido.</p>
    <?php if ($errores): ?><div class="aviso" role="alert"><ul><?php foreach ($errores as $error): ?><li><?= escapar($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <div class="layout-carrito">
        <form class="compra-formulario" method="post" action="finalizar-compra.php">
            <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
            <input type="hidden" name="compra" value="<?= escapar($_SESSION['compra']['clave'] ?? '') ?>">
            <label for="nombre">Nombre y apellido</label>
            <input id="nombre" name="nombre" autocomplete="name" maxlength="200" value="<?= escapar($nombre) ?>" required>
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= escapar($email) ?>" required>
            <label for="telefono">Teléfono de contacto</label>
            <input id="telefono" name="telefono" type="tel" autocomplete="tel" minlength="6" maxlength="30" value="<?= escapar($telefono) ?>" required>
            <h2>Entrega y pago</h2>
            <p>Retiro en tienda. Pago al retirar el pedido.</p>
            <p>Tu pedido quedará pendiente de confirmación para coordinar el retiro.</p>
            <button type="submit">Confirmar pedido</button>
            <p class="nota-precios">Precios orientativos para esta presentación.</p>
        </form>
        <aside class="resumen-carrito"><h2>Tu pedido</h2>
            <ul class="resumen-compra"><?php foreach ($resumen['items'] as $item): ?><li><?= escapar($item['producto']['nombre']) ?><br>Talle <?= escapar($item['talle']) ?> · <?= $item['cantidad'] ?> unidad(es)<br><strong><?= escapar(precio_ars(importe_decimal($item['subtotal']))) ?></strong></li><?php endforeach; ?></ul>
            <p>Total <strong><?= escapar(precio_ars(importe_decimal($resumen['total']))) ?></strong></p>
        </aside>
    </div>
    <p><a href="carrito.php">Volver al carrito</a></p>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
