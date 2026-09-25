<?php
$titulo_pagina = "Ropa Urbana en Salta | TF Clothing";
$descripcion_pagina = "Descubrí la mejor ropa urbana y streetwear en Salta. Buzos, remeras y más para hombre, mujer y niños. ¡Envíos a todo el país!";

require_once __DIR__ . '/includes/header.php';
?>

<a href="#main-content" style="position:absolute; left:-9999px;">
    Saltar al contenido principal
</a>

<?php require_once __DIR__ . '/includes/nav.php'; ?>

<hr>

<main id="main-content">
    <h1>TF - Ropa Urbana</h1>
    <p id="reloj"></p>
    <h2>Destacados</h2>
    <h2>Categorías</h2>

    <div class="categorias-grid">
        <div class="categoria-item">
            <h3>Hombre</h3>
            <a href="hombre.php">
                <img src="imagenes/hombre.png" alt="Ropa Urbana para Hombre">
            </a>
        </div>

        <div class="categoria-item">
            <h3>Mujer</h3>
            <a href="mujer.php">
                <img src="imagenes/mujer.png" alt="Ropa Urbana para Mujer">
            </a>
        </div>

        <div class="categoria-item">
            <h3>Niño/a</h3>
            <a href="niños.php">
                <img src="imagenes/niños.png" alt="Ropa Urbana para Niño/a">
            </a>
        </div>

        <div class="categoria-item">
            <h3>Accesorios</h3>
            <a href="accesorios.php">
                <img src="imagenes/accesorios.png" alt="Accesorios">
            </a>
        </div>
    </div>

    <p>
        TF - Tienda de ropa urbana<br>
        Av. Belgrano 1540<br>
        Salta, Argentina
    </p>
    <p>Envíos a todo el país. Cambios sin cargo dentro de los 10 días.</p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>