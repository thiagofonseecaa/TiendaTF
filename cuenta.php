<?php
require_once __DIR__ . '/auth_guard.php';
$titulo_cuenta = es_administrador() ? 'Panel de administración' : 'Mi cuenta';
$titulo_pagina = $titulo_cuenta . ' | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
    <h1><?= escapar($titulo_cuenta) ?></h1>
    <p>Hola, <?= escapar($_SESSION['usuario_nombre']) ?>.</p>
    <p><?= escapar($_SESSION['usuario_email']) ?> · <?= es_administrador() ? 'Administrador' : 'Cliente' ?></p>
    <p><?= es_administrador() ? 'Gestioná el catálogo y consultá los pedidos de los clientes.' : 'Consultá tus compras o seguí recorriendo la colección.' ?></p>
    <div class="panel-cuenta">
        <?php if (es_administrador()): ?>
            <a class="boton-tienda" href="admin/index.php">Gestionar productos</a>
            <a class="boton-tienda" href="admin/pedidos.php">Ver pedidos recibidos</a>
        <?php else: ?>
            <a class="boton-tienda" href="mis-pedidos.php">Mis pedidos</a>
            <a href="clase5/buscar.php">Ver la colección</a>
        <?php endif; ?>
    </div>
    <?php if (!es_administrador() && cantidad_carrito() > 0): ?><p class="nota-precios">Al cerrar sesión se vacía el carrito.</p><?php endif; ?>
    <form class="form-salir" method="post" action="logout.php">
        <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
        <button type="submit">Cerrar sesión</button>
    </form>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
