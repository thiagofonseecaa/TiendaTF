<?php
require_once __DIR__ . '/sesion.php';
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../clase5/funciones.php';

function url_tienda(string $pagina = ''): string
{
    global $configuracion;
    $base = $configuracion['APP_BASE_PATH'] ?? '/' . rawurlencode(basename(dirname(__DIR__))) . '/';
    return rtrim('/' . trim($base, '/'), '/') . '/' . ltrim($pagina, '/');
}

function esta_autenticado(): bool
{
    return isset($_SESSION['usuario_id'], $_SESSION['usuario_nombre'], $_SESSION['rol'])
        && is_int($_SESSION['usuario_id']) && $_SESSION['usuario_id'] > 0
        && is_string($_SESSION['usuario_nombre'])
        && in_array($_SESSION['rol'], ['admin', 'usuario'], true);
}

function es_administrador(): bool
{
    return esta_autenticado() && $_SESSION['rol'] === 'admin';
}

function rol_de_sesion(string $nombre): ?string
{
    // Conserva la relación roles/usuarios del TP6 y usa los valores del TP8 en la sesión.
    return ['administrador' => 'admin', 'cliente' => 'usuario'][$nombre] ?? null;
}

function csrf_valido(array $datos): bool
{
    return isset($datos['csrf']) && is_string($datos['csrf'])
        && hash_equals($_SESSION['csrf'], $datos['csrf']);
}

function mostrar_error_acceso(int $codigo, string $mensaje): never
{
    global $productos, $pdo, $nombre_sitio;
    http_response_code($codigo);
    $titulo_pagina = 'Acceso a tu cuenta | TF Clothing';
    $base_sitio = url_tienda();
    require __DIR__ . '/../includes/header.php';
    require __DIR__ . '/../includes/nav.php';
    echo '<main id="main-content" class="pagina-info"><h1>Acceso a tu cuenta</h1><p class="aviso" role="alert">'
        . escapar($mensaje) . '</p><p><a href="' . escapar(url_tienda('cuenta.php')) . '">Volver a mi cuenta</a></p></main>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}
