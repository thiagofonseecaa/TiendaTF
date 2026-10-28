<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/catalogo.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
$_SESSION['carrito'] ??= [];

// Los números de la lista anterior no eran los mismos ids de MySQL.
if (($_SESSION['origen_carrito'] ?? '') !== 'mysql') {
    if ($_SESSION['carrito']) {
        $_SESSION['aviso_carrito'] = 'Actualizamos el catálogo. Volvé a elegir tus productos para armar el pedido.';
    }
    $_SESSION['carrito'] = [];
    $_SESSION['origen_carrito'] = 'mysql';
}

function producto_por_id(string $id): ?array
{
    global $productos;
    foreach ($productos as $producto) {
        if ($producto['id'] === $id) return $producto;
    }
    return null;
}

function cantidad_carrito(): int
{
    return array_sum(array_column($_SESSION['carrito'], 'cantidad'));
}

function resumen_carrito(): array
{
    $items = [];
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $producto = producto_por_id($item['producto']);
        if (!$producto || !in_array($item['talle'], $producto['talles'], true)) continue;
        $subtotal = centavos($producto['precio']) * $item['cantidad'];
        $total += $subtotal;
        $items[] = ['producto' => $producto, 'talle' => $item['talle'], 'cantidad' => $item['cantidad'], 'subtotal' => $subtotal];
    }
    return ['items' => $items, 'total' => $total];
}

function firma_carrito(array $resumen): string
{
    $datos = [];
    foreach ($resumen['items'] as $item) {
        $datos[] = [$item['producto']['id'], $item['talle'], $item['cantidad'], $item['producto']['precio']];
    }
    return hash('sha256', json_encode($datos));
}

foreach ($_SESSION['carrito'] as $clave => $item) {
    $producto = producto_por_id($item['producto']);
    if (!$producto || !in_array($item['talle'], $producto['talles'], true)) {
        unset($_SESSION['carrito'][$clave], $_SESSION['compra']);
        $_SESSION['aviso_carrito'] = 'Quitamos del carrito un producto o talle que ya no está disponible. Revisá tu selección.';
    }
}
