<?php
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/catalogo.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$_SESSION['carrito'] ??= [];
function producto_por_id(string $id): ?array
{
    global $productos;
    foreach ($productos as $producto) {
        if ($producto['id'] === $id) {
            return $producto;
        }
    }
    return null;
}
function cantidad_carrito(): int
{
    return array_sum(array_column($_SESSION['carrito'], 'cantidad'));
}
