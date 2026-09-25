<?php
$titulo_pagina = "Descuentos | TF Clothing";
$descripcion_pagina = "Conocé las promociones de TF Clothing: cuotas sin interés y envío gratis.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Descuentos</h1>
    <h2>Código promocional</h2>

    <div class="categorias-grid">
        <div class="categoria-item promo">
            <h3>Cuotas sin interés</h3>
            <img src="imagenes/Cuotas.png" alt="Cuotas sin interés">
        </div>

        <div class="categoria-item promo">
            <h3>Envío gratis</h3>
            <img src="imagenes/enviogratis.png" alt="Envío gratis">
        </div>
    </div>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>