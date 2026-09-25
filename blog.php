<?php
$titulo_pagina = "Blog | TF Clothing";
$descripcion_pagina = "Enterate de las novedades, colecciones y promociones de TF Clothing.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Blog TF</h1>

    <h2>Llegó la nueva colección de invierno</h2>
    <h3>Buzos y camperas</h3>

    <div class="galeria-blog">
        <img src="imagenes/blog.invierno.png" alt="Camperas" class="img-blog">
        <img src="imagenes/blog.invierno2.png" alt="" class="img-blog">
    </div>

    <p>Ya están disponibles los nuevos buzos oversize y camperas urbanas para esta temporada.</p>

    <h3>Stock limitado</h3>
    <p>Algunos talles ya se están agotando, conviene reservar antes de que se acaben.</p>

    <h2>TF cumple 2 meses</h2>
    <h3>Un poco de historia</h3>
    <p>Repasamos cómo arrancó la tienda y cómo fue creciendo hasta hoy.</p>

    <h3>Descuento por aniversario</h3>
    <p>Por el aniversario, hay 20% de descuento en toda la web durante esta semana.</p>

    <h2>Comentarios</h2>
    <h3>Martín G.</h3>
    <p>Buenísima la nueva colección, ya compré un buzo.</p>

    <h3>Sofía R.</h3>
    <p>¿El descuento del aniversario es acumulable con otras promos?</p>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>