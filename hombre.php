<?php
$titulo_pagina = "Ropa de Hombre | TF Clothing";
$descripcion_pagina = "Descubrí la colección de ropa urbana para hombre de TF Clothing.";
$categoria = "hombre";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Ropa de Hombre</h1>

    <div class="galeria">
        <button id="btn-anterior">&#8592; Anterior</button>

        <div>
            <img
                id="galeria-imagen"
                src="imagenes/remerahombre.png"
                alt="Producto de hombre"
            >
            <p id="galeria-nombre">Remeras</p>
        </div>

        <button id="btn-siguiente">Siguiente &#8594;</button>
    </div>

    <div id="modal-imagen" class="modal-oculto">
        <span id="cerrar-modal">&times;</span>
        <img id="modal-imagen-grande" src="" alt="Imagen ampliada">
    </div>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>