<?php
require_once __DIR__ . '/../config/conexion.php';

function centavos(string $importe): int
{
    $partes = explode('.', $importe);
    return (int) $partes[0] * 100 + (int) str_pad($partes[1] ?? '', 2, '0');
}

function importe_decimal(int $centavos): string
{
    return intdiv($centavos, 100) . '.' . str_pad((string) ($centavos % 100), 2, '0', STR_PAD_LEFT);
}

function precio_ars(string $precio): string
{
    return '$ ' . number_format((float) $precio, 2, ',', '.');
}

function cargar_productos(): array
{
    global $pdo;
    $consulta = $pdo->prepare('SELECT p.id, p.sku, p.nombre, p.descripcion, p.precio, p.imagen,
                                    c.nombre AS categoria, t.talle
        FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN producto_talles t ON t.producto_id = p.id
        WHERE p.activo = :activo ORDER BY p.id, t.id');
    $consulta->execute(['activo' => 1]);
    $catalogo = [];
    foreach ($consulta->fetchAll() as $fila) {
        $id = (string) $fila['id'];
        if (!isset($catalogo[$id])) {
            $catalogo[$id] = $fila;
            $catalogo[$id]['id'] = $id;
            $catalogo[$id]['talles'] = [];
            $catalogo[$id]['imagen'] = imagen_producto($fila['imagen']);
            $catalogo[$id]['terminos'] = $fila['categoria'] . ' ' . ($fila['descripcion'] ?? '')
                . ' ' . str_replace(['remera', 'pantalón', 'buzo', 'gorra', 'cinto', 'anillo', 'reloj'],
                  ['remeras', 'pantalones pantalon', 'buzos', 'gorras', 'cintos cinturones', 'anillos', 'relojes'], mb_strtolower($fila['nombre']));
        }
        if ($fila['talle'] !== null) {
            $catalogo[$id]['talles'][] = $fila['talle'];
        }
    }
    return array_values($catalogo);
}

function imagen_producto(?string $ruta): string
{
    if ($ruta && preg_match('~^(clase5/imagenes|imagenes)/[a-zA-Z0-9_-]+\.(png|jpg|jpeg|webp)$~D', $ruta)
        && is_file(__DIR__ . '/../' . $ruta)) {
        return $ruta;
    }
    return 'imagenes/producto-sin-foto.svg';
}

try {
    $productos = cargar_productos();
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('No se pudo cargar el catálogo. Revisá la conexión y la actualización de la tienda.');
}
