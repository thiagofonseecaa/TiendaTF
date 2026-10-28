<?php
require_once __DIR__ . '/tienda.php';
$base_sitio = '../';
$titulo_pagina = 'Catálogo | TF Clothing';
$busqueda = obtener_campo($_GET, 'q');
$enviado = isset($_GET['q']);
$error = '';
$resultados = $productos;
if ($enviado) {
    $resultados = [];
    if ($busqueda === '') {
        $error = 'Ingresá un producto para buscar.';
    } elseif (mb_strlen($busqueda) > 80) {
        $error = 'La búsqueda no puede superar los 80 caracteres.';
    } else {
        foreach ($productos as $producto) {
            if (mb_stripos($producto['nombre'] . ' ' . $producto['terminos'], $busqueda) !== false) {
                $resultados[] = $producto;
            }
        }
    }
}
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-catalogo">
    <p class="sobretitulo">TF / COLECCIÓN</p>
    <h1><?= $enviado ? 'Resultados de búsqueda' : 'Todas las prendas' ?></h1>
    <p>Ropa para todos los días. Encontrá tu próximo favorito.</p>
    <form class="buscador-catalogo" action="buscar.php" method="get" novalidate>
        <label for="q">Buscar productos</label>
        <div><input type="search" id="q" name="q" maxlength="80" value="<?= escapar($busqueda) ?>" placeholder="Remeras, pantalones, buzos…" required><button type="submit">Buscar</button></div>
    </form>
    <?php if ($error !== ''): ?><p class="aviso" role="alert"><?= escapar($error) ?></p>
    <?php elseif ($enviado): ?>
        <h2>Resultados para: <?= escapar($busqueda) ?></h2>
        <p role="status"><?= empty($resultados) ? 'No encontramos productos con ese nombre.' : 'Productos encontrados: ' . count($resultados) ?></p>
    <?php endif; ?>
    <p class="nota-precios">Precios orientativos en pesos argentinos para esta presentación.</p>
    <?php require __DIR__ . '/tarjetas.php'; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
