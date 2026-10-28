<?php
require_once __DIR__ . '/config/autenticacion.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    mostrar_error_acceso(405, 'Para cerrar la sesión usá el botón de tu cuenta.');
}
if (!csrf_valido($_POST)) mostrar_error_acceso(403, 'El formulario venció. Volvé a tu cuenta e intentá otra vez.');
$destino = url_tienda('login.php?salida=ok');
$parametros = session_get_cookie_params();
$_SESSION = [];
session_unset();
if (ini_get('session.use_cookies')) {
    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $parametros['path'],
        'domain' => $parametros['domain'],
        'secure' => $parametros['secure'],
        'httponly' => $parametros['httponly'],
        'samesite' => $parametros['samesite'] ?? 'Lax',
    ]);
}
session_destroy();
header('Location: ' . $destino, true, 303);
exit;
