<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../clase5/tienda.php';

$base_sitio = '../';
$titulo_pagina = 'Editar producto | TF Clothing';
$es_post = $_SERVER['REQUEST_METHOD'] === 'POST';
$id_recibido = obtener_campo($es_post ? $_POST : $_GET, 'id');
$id = filter_var($id_recibido, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
$sku = obtener_campo($_POST, 'sku');
$nombre = obtener_campo($_POST, 'nombre');
$descripcion = obtener_campo($_POST, 'descripcion');
$categoria_id = obtener_campo($_POST, 'categoria_id');
$precio = obtener_campo($_POST, 'precio');
$activo = obtener_campo($_POST, 'activo');
$errores = [];
$categorias = [];
$producto_encontrado = false;
$categorias_cargadas = false;

if ($id === false) {
    http_response_code(400);
    $errores[] = 'Seleccioná un producto válido desde el listado.';
} else {
    try {
        $consulta = $pdo->prepare('SELECT sku, nombre, descripcion, categoria_id, precio, activo FROM productos WHERE id = :id');
        $consulta->execute(['id' => $id]);
        $producto_actual = $consulta->fetch();
        if (!$producto_actual) {
            http_response_code(404);
            $errores[] = 'El producto que querés editar no existe.';
        } else {
            $producto_encontrado = true;
            if (!$es_post) {
                $sku = $producto_actual['sku'];
                $nombre = $producto_actual['nombre'];
                $descripcion = $producto_actual['descripcion'] ?? '';
                $categoria_id = (string) $producto_actual['categoria_id'];
                $precio = str_replace('.', ',', $producto_actual['precio']);
                $activo = (string) $producto_actual['activo'];
            }
            $consulta = $pdo->prepare('SELECT id, nombre FROM categorias WHERE id > :id ORDER BY nombre ASC');
            $consulta->execute(['id' => 0]);
            $categorias = $consulta->fetchAll();
            $categorias_cargadas = true;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        http_response_code(503);
        $errores[] = 'No se pudieron cargar los datos del producto. Intentá nuevamente más tarde.';
    }
}

if ($es_post && $producto_encontrado && $categorias_cargadas) {
    $sku = strtoupper($sku);
    if (!hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf'))) {
        http_response_code(403);
        $errores[] = 'El formulario venció. Volvé a intentarlo.';
    }
    if (!preg_match('/^[A-Z0-9][A-Z0-9_-]{0,39}$/D', $sku)) {
        $errores[] = 'Ingresá un código de hasta 40 caracteres, con letras, números o guiones.';
    }
    if ($nombre === '' || mb_strlen($nombre) > 120) {
        $errores[] = 'Ingresá un nombre de hasta 120 caracteres.';
    }
    if (mb_strlen($descripcion) > 2000) {
        $errores[] = 'La descripción no puede superar los 2000 caracteres.';
    }
    $ids_categorias = array_map('strval', array_column($categorias, 'id'));
    if (!in_array($categoria_id, $ids_categorias, true)) {
        $errores[] = 'Seleccioná una categoría de la lista.';
    }
    if (!preg_match('/^[0-9]{1,8}([.,][0-9]{1,2})?$/D', $precio)) {
        $errores[] = 'Ingresá un precio válido, sin puntos de miles y con hasta dos decimales.';
    }
    if (!in_array($activo, ['0', '1'], true)) {
        $errores[] = 'Seleccioná un estado válido.';
    }
    if ($errores && http_response_code() !== 403) {
        http_response_code(422);
    }
    if (empty($errores)) {
        try {
            $sql = 'UPDATE productos
                    SET categoria_id = :categoria_id, sku = :sku, nombre = :nombre,
                        descripcion = :descripcion, precio = :precio, activo = :activo
                    WHERE id = :id';
            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                'categoria_id' => $categoria_id,
                'sku' => $sku,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => str_replace(',', '.', $precio),
                'activo' => $activo,
                'id' => $id,
            ]);
            $_SESSION['aviso_productos'] = 'Producto actualizado correctamente.';
            header('Location: index.php', true, 303);
            exit;
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? null) === 1062) {
                http_response_code(422);
                $errores[] = 'Ya existe un producto con ese código. Elegí otro.';
            } else {
                error_log($e->getMessage());
                http_response_code(503);
                $errores[] = 'No se pudieron guardar los cambios. Intentá nuevamente más tarde.';
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/nav.php';
?>
<main id="main-content" class="pagina-info gestion-productos">
    <h1>Editar producto</h1>
    <p>Modificá los datos del producto y guardá los cambios.</p>

    <?php if ($errores): ?>
        <div class="aviso" role="alert">
            <ul><?php foreach ($errores as $error): ?><li><?= escapar($error) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <?php if ($producto_encontrado && $categorias_cargadas): ?>
    <form class="form-producto" method="post" action="editar.php">
        <input type="hidden" name="id" value="<?= escapar((string) $id) ?>">
        <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">

        <label for="sku">Código del producto</label>
        <input id="sku" name="sku" maxlength="40" pattern="[A-Za-z0-9][A-Za-z0-9_-]{0,39}" value="<?= escapar($sku) ?>" placeholder="TF-BUZ-H02" required>

        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" maxlength="120" value="<?= escapar($nombre) ?>" required>

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">Elegí una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= escapar((string) $categoria['id']) ?>"<?= $categoria_id === (string) $categoria['id'] ? ' selected' : '' ?>><?= escapar($categoria['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="precio">Precio en pesos argentinos</label>
        <input id="precio" name="precio" inputmode="decimal" maxlength="11" pattern="[0-9]{1,8}([.,][0-9]{1,2})?" value="<?= escapar($precio) ?>" aria-describedby="ayuda-precio" placeholder="24900,50" required>
        <p id="ayuda-precio" class="nota-precios">Sin separador de miles. Ejemplos: 24900 o 24900,50.</p>

        <label for="descripcion">Descripción (opcional)</label>
        <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000"><?= escapar($descripcion) ?></textarea>

        <label for="activo">Estado</label>
        <select id="activo" name="activo" required>
            <option value="1"<?= $activo === '1' ? ' selected' : '' ?>>Activo</option>
            <option value="0"<?= $activo === '0' ? ' selected' : '' ?>>Inactivo</option>
        </select>

        <div class="acciones-producto">
            <button type="submit">Guardar cambios</button>
            <a href="index.php">Volver al listado</a>
        </div>
    </form>
    <?php else: ?>
        <p><a href="index.php">Volver al listado</a></p>
    <?php endif; ?>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
