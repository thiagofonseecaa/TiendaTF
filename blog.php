<?php
$titulo_pagina = 'Blog TF | TF Clothing';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
<p class="sobretitulo">TF CLOTHING</p>
<h1>Blog TF</h1>
<p class="intro-info">Ideas para combinar las prendas de la tienda y conocer mejor tu ropa.</p>
<div class="articulos-info">
<article class="bloque-info"><img class="imagen-info" width="280" height="180" src="clase5/imagenes/remera-clasica.png" alt="Remera azul del catálogo TF"><p class="etiqueta-info">COMBINACIONES</p><h2>Una remera básica, tres combinaciones</h2><p>Una remera lisa puede ser el punto de partida de distintos conjuntos. Con un jean recto, tenés una combinación sencilla para todos los días. Con un jogger, una opción más relajada. Si refresca, podés sumar un buzo con cierre.</p><p>Probá primero una base de dos colores y sumá un accesorio que te guste. No necesitás usar todas las prendas al mismo tiempo.</p><a href="hombre.php">Ver prendas de hombre</a></article>
<article class="bloque-info"><img class="imagen-info" width="280" height="180" src="clase5/imagenes/pantalon-mujer.png" alt="Jogger de mujer del catálogo TF"><p class="etiqueta-info">TU ESTILO</p><h2>Elegí el calce que te resulta cómodo</h2><p>El talle y el calce son cosas distintas: dos prendas con la misma letra pueden tener formas diferentes. Una remera ajustada queda cerca del cuerpo; una de corte amplio deja más espacio.</p><p>Antes de elegir, compará las medidas y pensá cómo te gusta usar la ropa. Para los pantalones, revisá también el largo y la cintura.</p><a href="guia-de-talles.php">Cómo tomar tus medidas</a></article>
<article class="bloque-info"><img class="imagen-info" width="280" height="180" src="imagenes/gorras.png" alt="Gorra de la sección Accesorios"><p class="etiqueta-info">ACCESORIOS</p><h2>Los detalles completan el conjunto</h2><p>Una gorra, un cinto o un reloj pueden acompañar una combinación básica. Podés empezar con un solo accesorio y elegir un color que ya esté presente en tu ropa.</p><p>La idea es que el conjunto se adapte a vos y a lo que vas a hacer ese día.</p><a href="accesorios.php">Explorar accesorios</a></article>
</div>
<p class="volver-info"><a href="index.php">Volver al inicio</a></p>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
