<?php
require_once __DIR__ . '/auth_guard.php';
$titulo_pagina = 'Mi cuenta | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
    <h1>Mi cuenta</h1>
    <p>Hola, <?= escapar($_SESSION['usuario_nombre']) ?>.</p>
    <p><?= escapar($_SESSION['usuario_email']) ?> · <?= es_administrador() ? 'Administrador' : 'Cliente' ?></p>
    <div class="panel-cuenta">
        <a class="boton-tienda" href="mis-pedidos.php">Mis pedidos</a>
        <?php if (es_administrador()): ?>
            <a class="boton-tienda" href="admin/index.php">Gestionar productos</a>
            <a href="admin/pedidos.php">Ver pedidos recibidos</a>
        <?php else: ?><a href="clase5/buscar.php">Ver la colección</a><?php endif; ?>
    </div>
    <?php if (cantidad_carrito() > 0): ?><p class="nota-precios">Al cerrar sesión se vacía el carrito.</p><?php endif; ?>
    <form class="form-salir" method="post" action="logout.php">
        <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
        <button type="submit">Cerrar sesión</button>
    </form>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
