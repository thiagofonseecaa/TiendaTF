<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../clase5/tienda.php';

$base_sitio = '../';
$titulo_pagina = 'Gestión de productos | TF Clothing';
$busqueda = obtener_campo($_GET, 'q');
$productos_listados = [];
$error = '';
$aviso = $_SESSION['aviso_productos'] ?? '';
unset($_SESSION['aviso_productos']);

if (isset($_GET['q']) && !is_string($_GET['q'])) {
    $error = 'La búsqueda no es válida.';
    http_response_code(400);
} elseif (mb_strlen($busqueda) > 80) {
    $error = 'La búsqueda no puede superar los 80 caracteres.';
    http_response_code(400);
} else {
    try {
        $sql = 'SELECT p.id, p.sku, p.nombre, p.precio, p.activo, c.nombre AS categoria
                FROM productos AS p
                INNER JOIN categorias AS c ON p.categoria_id = c.id
                WHERE p.nombre LIKE :busqueda
                ORDER BY p.id DESC';
        $consulta = $pdo->prepare($sql);
        $consulta->execute(['busqueda' => '%' . $busqueda . '%']);
        $productos_listados = $consulta->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(503);
        $error = 'No se pudo cargar el listado de productos. Intentá nuevamente más tarde.';
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-info gestion-productos">
    <h1>Gestión de productos</h1>
    <p>Consultá los productos guardados en la base de datos de TF.</p>
    <?php if ($aviso !== ''): ?><p class="aviso" role="status"><?= escapar($aviso) ?></p><?php endif; ?>
    <p><a class="boton-tienda" href="crear.php">Agregar producto</a></p>

    <form class="buscador-catalogo" method="get" action="index.php">
        <label for="buscar-producto">Buscar por nombre</label>
        <div>
            <input id="buscar-producto" type="search" name="q" maxlength="80" value="<?= escapar($busqueda) ?>" placeholder="Por ejemplo: remera">
            <button type="submit">Buscar</button>
        </div>
    </form>

    <?php if ($error !== ''): ?>
        <p class="aviso" role="alert"><?= escapar($error) ?></p>
    <?php else: ?>
        <p role="status">Productos encontrados: <?= escapar((string) count($productos_listados)) ?></p>
        <?php if (empty($productos_listados)): ?>
            <p>No se encontraron productos con ese nombre.</p>
        <?php else: ?>
            <div class="tabla-info">
                <table>
                    <caption>Listado de productos</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Código</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Categoría</th>
                            <th scope="col">Precio (ARS)</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_listados as $producto): ?>
                            <tr>
                                <td><?= escapar((string) $producto['id']) ?></td>
                                <td><?= escapar($producto['sku']) ?></td>
                                <td><?= escapar($producto['nombre']) ?></td>
                                <td><?= escapar($producto['categoria']) ?></td>
                                <td class="precio-tabla"><?= escapar('$ ' . number_format((float) $producto['precio'], 2, ',', '.')) ?></td>
                                <td><?= escapar((int) $producto['activo'] === 1 ? 'Activo' : 'Inactivo') ?></td>
                                <td><div class="acciones-tabla">
                                    <a href="editar.php?id=<?= escapar((string) $producto['id']) ?>" aria-label="<?= escapar('Editar ' . $producto['nombre']) ?>">Editar</a>
                                    <a href="eliminar.php?id=<?= escapar((string) $producto['id']) ?>" class="enlace-eliminar" aria-label="<?= escapar('Eliminar ' . $producto['nombre']) ?>">Eliminar</a>
                                </div></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <p class="volver-info"><a href="../index.php">Volver a la tienda</a></p>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
