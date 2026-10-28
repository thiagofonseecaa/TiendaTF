<?php
require_once __DIR__ . '/clase5/tienda.php';
if (esta_autenticado()) {
    require_once __DIR__ . '/auth_guard.php';
    verificar_rol('usuario');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = obtener_campo($_POST, 'accion');
    $id = obtener_campo($_POST, 'producto');
    $talle = obtener_campo($_POST, 'talle');
    $producto = producto_por_id($id);
    $clave = $id . ':' . $talle;
    if (!hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf'))) {
        $_SESSION['aviso_carrito'] = 'El formulario venció. Intentá nuevamente.';
    } elseif ($accion === 'quitar') {
        unset($_SESSION['carrito'][$clave], $_SESSION['compra']);
        $_SESSION['aviso_carrito'] = 'Producto quitado del carrito.';
    } elseif (!$producto || !in_array($talle, $producto['talles'], true)) {
        $_SESSION['aviso_carrito'] = 'Elegí un producto y un talle válidos.';
    } elseif ($accion === 'agregar' || $accion === 'actualizar') {
        $cantidad = $accion === 'agregar' ? ($_SESSION['carrito'][$clave]['cantidad'] ?? 0) + 1
            : filter_var(obtener_campo($_POST, 'cantidad'), FILTER_VALIDATE_INT);
        if ($cantidad === false || $cantidad < 1 || $cantidad > 10 ||
            ($accion === 'actualizar' && !isset($_SESSION['carrito'][$clave]))) {
            $_SESSION['aviso_carrito'] = 'La cantidad debe estar entre 1 y 10.';
        } else {
            $_SESSION['carrito'][$clave] = ['producto' => $id, 'talle' => $talle, 'cantidad' => $cantidad];
            unset($_SESSION['compra']);
            $_SESSION['aviso_carrito'] = $accion === 'agregar' ? 'Producto agregado al carrito.' : 'Cantidad actualizada.';
        }
    } else {
        $_SESSION['aviso_carrito'] = 'La acción no es válida.';
    }
    header('Location: carrito.php', true, 303);
    exit;
}
$titulo_pagina = 'Tu carrito | TF Clothing';
$aviso = $_SESSION['aviso_carrito'] ?? '';
unset($_SESSION['aviso_carrito']);
$resumen = resumen_carrito();
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-catalogo">
    <p class="sobretitulo">TF / TU SELECCIÓN</p><h1>Tu carrito</h1>
    <?php if ($aviso !== ''): ?><p class="aviso" role="status"><?= escapar($aviso) ?></p><?php endif; ?>
    <?php if (!$resumen['items']): ?>
        <div class="carrito-vacio"><h2>Tu carrito está vacío</h2><p>Encontrá una prenda que vaya con vos.</p><a class="boton-tienda" href="clase5/buscar.php">Explorar colección</a></div>
    <?php else: ?>
    <div class="layout-carrito"><div class="items-carrito">
        <?php foreach ($resumen['items'] as $item): $producto = $item['producto']; ?>
        <article class="item-carrito">
            <img src="<?= escapar($producto['imagen']) ?>" alt="<?= escapar($producto['nombre']) ?>">
            <div><p class="categoria-producto"><?= escapar($producto['categoria']) ?></p><h2><?= escapar($producto['nombre']) ?></h2><p>Talle <?= escapar($item['talle']) ?> · <?= escapar(precio_ars($producto['precio'])) ?> por unidad</p>
                <form class="form-cantidad" method="post" action="carrito.php">
                    <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
                    <input type="hidden" name="producto" value="<?= escapar($producto['id']) ?>">
                    <input type="hidden" name="talle" value="<?= escapar($item['talle']) ?>">
                    <label>Cantidad <input type="number" name="cantidad" min="1" max="10" value="<?= $item['cantidad'] ?>" required></label>
                    <button name="accion" value="actualizar">Actualizar</button><button name="accion" value="quitar" class="boton-secundario" formnovalidate>Quitar</button>
                </form><p class="precio-producto"><?= escapar(precio_ars(importe_decimal($item['subtotal']))) ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div><aside class="resumen-carrito"><h2>Resumen</h2><p>Productos <strong><?= cantidad_carrito() ?></strong></p><p>Total <strong><?= escapar(precio_ars(importe_decimal($resumen['total']))) ?></strong></p><p class="nota-precios">Retiro en tienda. Pago al retirar.</p><a class="boton-tienda" href="finalizar-compra.php">Continuar compra</a><p class="nota-precios">Precios orientativos para esta presentación.</p></aside></div>
    <?php endif; ?>
    <p><a href="clase5/buscar.php">Seguir comprando</a></p>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
