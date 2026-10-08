<?php
require_once __DIR__ . '/funciones.php';

$busqueda = obtener_campo($_GET, 'q');
$enviado = isset($_GET['q']);
$error = '';
$resultados = [];

$productos = [
    ['nombre' => 'Remera azul de hombre', 'imagen' => 'remera-clasica.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'remera remeras algodón lisa'],
    ['nombre' => 'Jean recto de hombre', 'imagen' => 'jean-recto.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'pantalon pantalón pantalones jean jeans'],
    ['nombre' => 'Buzo negro con cierre de hombre', 'imagen' => 'buzo-infantil.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'buzo buzos elegante'],
    ['nombre' => 'Remera blanca de mujer', 'imagen' => 'remera-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'remera remeras algodón'],
    ['nombre' => 'Pantalón jogger de mujer', 'imagen' => 'pantalon-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'pantalon pantalones jogger'],
    ['nombre' => 'Buzo negro de mujer', 'imagen' => 'buzo-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'buzo buzos'],
    ['nombre' => 'Remera blanca infantil', 'imagen' => 'remera-infantil.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'remera remeras niño niña niños niñas nino nina ninos ninas'],
    ['nombre' => 'Pantalón jogger infantil', 'imagen' => 'pantalon-infantil.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'pantalon pantalones niño niña niños niñas nino nina ninos ninas'],
    ['nombre' => 'Buzo gris infantil', 'imagen' => 'buzo-clasico.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'buzo buzos niño niña niños niñas nino nina ninos ninas']
];

if ($enviado) {
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
if (!$enviado) {
    $resultados = $productos;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar productos | TF Clothing</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="catalogo.css">
</head>
<body>
    <nav>
        <a href="../index.php">TF</a> |
        <a href="contacto.php">Contacto</a> |
        <a href="buscar.php" class="active">Buscar productos</a>
    </nav>

    <main id="main-content">
        <h1>Buscar productos</h1>
        <p>Ingresá el nombre del producto que buscás.</p>

        <form action="buscar.php" method="get" novalidate>
            <p>
                <label for="q">Producto:</label>
                <input
                    type="search"
                    id="q"
                    name="q"
                    maxlength="80"
                    value="<?= escapar($busqueda) ?>"
                    required
                >
            </p>
            <p>
                <button type="submit">Buscar</button>
            </p>
        </form>

        <?php if ($error !== ''): ?>
            <p role="alert"><?= escapar($error) ?></p>
        <?php elseif ($enviado): ?>
            <h2>Resultados para: <?= escapar($busqueda) ?></h2>

            <?php if (empty($resultados)): ?>
                <p role="status">No encontramos productos con ese nombre.</p>
            <?php else: ?>
                <p role="status">
                    Productos encontrados: <?= count($resultados) ?>
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($error === '' && !empty($resultados)): ?>
            <?php if (!$enviado): ?>
                <h2>Productos disponibles</h2>
            <?php endif; ?>
            <div class="catalogo-productos">
                <?php foreach ($resultados as $producto): ?>
                    <article class="producto">
                        <img src="imagenes/<?= escapar($producto['imagen']) ?>"
                             alt="<?= escapar($producto['nombre']) ?>" loading="lazy">
                        <h3><?= escapar($producto['nombre']) ?></h3>
                        <p><?= escapar($producto['categoria']) ?></p>
                        <p><a href="../<?= escapar($producto['seccion']) ?>">Ver sección</a></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <p><a href="../index.php">Volver a la tienda</a></p>
    </main>

    <footer>
        <p>© <?= date('Y') ?> TF Clothing</p>
    </footer>
</body>
</html>
