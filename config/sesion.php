<?php
// Se configura antes de enviar HTML y se comparte entre la cuenta y el carrito.
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_trans_sid', '0');
    $usa_https = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $usa_https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
header('Cache-Control: no-store, private, max-age=0');

if (isset($_SESSION['usuario_id'])) {
    if (time() - ($_SESSION['ultima_actividad'] ?? 0) > 1800) {
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nombre'], $_SESSION['usuario_email'],
            $_SESSION['rol'], $_SESSION['ultima_actividad'], $_SESSION['pedido_confirmado'], $_SESSION['compra']);
        session_regenerate_id(true);
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        $_SESSION['aviso_autenticacion'] = 'Tu sesión venció por inactividad. Volvé a ingresar.';
    } else {
        $_SESSION['ultima_actividad'] = time();
    }
}
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
