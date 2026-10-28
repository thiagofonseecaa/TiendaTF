<?php
require_once __DIR__ . '/../auth_guard.php';
verificar_rol('admin');
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../clase5/tienda.php';

$base_sitio = '../';
$titulo_pagina = 'Eliminar producto | TF Clothing';
$es_post = $_SERVER['REQUEST_METHOD'] === 'POST';
$id_recibido = obtener_campo($es_post ? $_POST : $_GET, 'id');
$id = filter_var($id_recibido, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
$producto = false;
$error = '';
$bloqueado = false;

if ($id === false) {
    http_response_code(400);
    $error = 'Seleccioná un producto válido desde el listado.';
} else {
    try {
        $sql = 'SELECT p.id, p.sku, p.nombre, p.precio, c.nombre AS categoria
                FROM productos AS p
                INNER JOIN categorias AS c ON p.categoria_id = c.id
                WHERE p.id = :id';
        $consulta = $pdo->prepare($sql);
        $consulta->execute(['id' => $id]);
        $producto = $consulta->fetch();
        if (!$producto) {
            http_response_code(404);
            $error = 'El producto que querés eliminar no existe.';
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(503);
        $error = 'No se pudieron cargar los datos del producto. Intentá nuevamente más tarde.';
    }
}

if ($es_post && $producto) {
    if (!hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf'))) {
        http_response_code(403);
        $error = 'El formulario venció. Volvé a intentarlo.';
    } elseif (obtener_campo($_POST, 'confirmar') !== 'si') {
        http_response_code(422);
        $error = 'Confirmá la eliminación con el botón del formulario.';
    } else {
        try {
            $consulta = $pdo->prepare('DELETE FROM productos WHERE id = :id');
            $consulta->execute(['id' => $id]);
            if ($consulta->rowCount() === 1) {
                $_SESSION['aviso_productos'] = 'Producto eliminado correctamente.';
                header('Location: index.php', true, 303);
                exit;
            }
            http_response_code(404);
            $producto = false;
            $error = 'El producto ya no existe.';
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1451) {
                http_response_code(409);
                $bloqueado = true;
                $error = 'Este producto tiene pedidos asociados y no se puede eliminar. Podés marcarlo como inactivo desde Editar.';
            } else {
                error_log($e->getMessage());
                http_response_code(503);
                $error = 'No se pudo eliminar el producto. Intentá nuevamente más tarde.';
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-info gestion-productos">
    <h1>Eliminar producto</h1>

    <?php if ($error !== ''): ?><p class="aviso" role="alert"><?= escapar($error) ?></p><?php endif; ?>

    <?php if ($producto): ?>
        <section class="bloque-info confirmacion-producto" aria-label="Producto seleccionado">
            <h2><?= escapar($producto['nombre']) ?></h2>
            <p><strong>Código:</strong> <?= escapar($producto['sku']) ?></p>
            <p><strong>Categoría:</strong> <?= escapar($producto['categoria']) ?></p>
            <p><strong>Precio:</strong> <?= escapar('$ ' . number_format((float) $producto['precio'], 2, ',', '.')) ?></p>
        </section>

        <?php if (!$bloqueado): ?>
            <p>¿Querés eliminar este producto? Esta acción no se puede deshacer.</p>
            <form method="post" action="eliminar.php" class="form-producto">
                <input type="hidden" name="id" value="<?= escapar((string) $producto['id']) ?>">
                <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
                <div class="acciones-producto">
                    <button type="submit" name="confirmar" value="si" class="boton-eliminar">Sí, eliminar producto</button>
                    <a href="index.php">Cancelar y volver</a>
                </div>
            </form>
        <?php else: ?>
            <p><a href="editar.php?id=<?= escapar((string) $producto['id']) ?>">Editar este producto</a></p>
            <p><a href="index.php">Volver al listado</a></p>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="index.php">Volver al listado</a></p>
    <?php endif; ?>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
