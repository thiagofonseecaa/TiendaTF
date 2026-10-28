<?php
$titulo_pagina = "Quiénes somos | TF Clothing";
$descripcion_pagina = "Conocé la historia, la misión y el equipo de TF Clothing, tienda de ropa urbana de Salta.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content" class="pagina-nosotros">
    <h1>Quiénes somos</h1>
    <div class="nosotros-contenido">
        <figure class="nosotros-imagen">
            <img src="imagenes/nosotros-tienda-urbana.png" alt="Imagen ilustrativa de una tienda de ropa urbana con prendas en tonos neutros y luz natural" width="400" height="400">
        </figure>
        <div class="nosotros-texto">
            <p>TF es una tienda de ropa urbana de Salta que nació con la idea de ofrecer prendas y calzado con identidad propia, a buen precio.</p>

            <h2>Nuestra misión</h2>
            <p>Acercar moda urbana de calidad a todo el país.</p>

            <h2>Nuestro equipo</h2>
            <p>Un equipo joven apasionado por el estilo urbano y la atención al cliente.</p>
        </div>
    </div>
    <p class="nosotros-volver"><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>