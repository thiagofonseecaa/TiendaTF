<?php
$titulo_pagina = "Accesorios | TF Clothing";
$descripcion_pagina = "Descubrí los accesorios de TF Clothing para completar tu estilo urbano.";
$categoria = "accesorios";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Accesorios</h1>

    <div class="galeria">
        <button id="btn-anterior">&#8592; Anterior</button>

        <div>
            <img
                id="galeria-imagen"
                src="imagenes/gorras.png"
                alt="Producto accesorio"
            >
            <p id="galeria-nombre">Gorras</p>
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