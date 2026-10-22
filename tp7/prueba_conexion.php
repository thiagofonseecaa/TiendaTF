<?php
require_once __DIR__ . '/../config/conexion.php';
$base_sitio = '../';
$titulo_pagina = 'Prueba de conexión | TF Clothing';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-info">
    <h1>Prueba de conexión</h1>
    <p class="aviso" role="status">La conexión con MySQL se realizó correctamente.</p>
    <p>TF Clothing ya puede comunicarse con su base de datos.</p>
    <p><a class="boton-tienda" href="../admin/index.php">Ver productos de MySQL</a></p>
    <p><a href="../index.php">Volver a la tienda</a></p>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
