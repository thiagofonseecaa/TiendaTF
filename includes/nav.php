<?php
$base_sitio = $base_sitio ?? '';
$pagina_actual = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
<header class="encabezado-tienda">
    <nav class="nav-tienda" aria-label="Navegación principal">
        <a class="logo-tf" href="<?= escapar($base_sitio) ?>index.php" aria-label="TF Clothing, inicio"><span>TF</span><small>CLOTHING</small></a>
        <div class="nav-categorias">
            <?php foreach (['clase5/buscar.php' => 'Catálogo', 'hombre.php' => 'Hombre', 'mujer.php' => 'Mujer', 'niños.php' => 'Niño/a', 'accesorios.php' => 'Accesorios', 'clase5/contacto.php' => 'Contacto'] as $ruta => $etiqueta): ?>
                <a href="<?= escapar($base_sitio . $ruta) ?>"<?= $pagina_actual === basename($ruta) ? ' class="active"' : '' ?>><?= escapar($etiqueta) ?></a>
            <?php endforeach; ?>
        </div>
        <div class="nav-acciones">
            <button id="btn-modo-oscuro" type="button">Modo oscuro</button>
            <a class="icono-cuenta" href="<?= escapar($base_sitio) ?><?= esta_autenticado() ? 'cuenta.php' : 'login.php' ?>" aria-label="<?= esta_autenticado() ? 'Mi cuenta' : 'Iniciar sesión' ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/></svg></a>
            <a class="icono-carrito" href="<?= escapar($base_sitio) ?>carrito.php" aria-label="Carrito, <?= cantidad_carrito() ?> prendas"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14l1 14H4L5 7Z M9 8V6a3 3 0 0 1 6 0v2"/></svg><span><?= cantidad_carrito() ?></span></a>
        </div>
    </nav>
</header>
