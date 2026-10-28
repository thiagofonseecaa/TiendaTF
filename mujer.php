<?php
require_once __DIR__ . '/clase5/tienda.php';
$titulo_pagina = 'Mujer | TF Clothing';
$resultados = array_values(array_filter($productos, fn($producto) => $producto['categoria'] === 'Mujer'));
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-catalogo">
    <p class="sobretitulo">TF / COLECCIÓN</p>
    <h1>Mujer</h1>
    <p>Prendas cómodas, combinaciones simples y tu propio estilo.</p>
    <p class="nota-precios">Precios orientativos en pesos argentinos para esta presentación.</p>
    <?php require __DIR__ . '/clase5/tarjetas.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
