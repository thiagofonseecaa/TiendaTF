<?php
require_once __DIR__ . '/config/autenticacion.php';

if (!isset($_SESSION['usuario_id']) || !esta_autenticado()) {
    header('Location: ' . url_tienda('login.php?error=acceso_denegado'), true, 303);
    exit;
}
require_once __DIR__ . '/config/conexion.php';
// Se vuelve a consultar el rol: un permiso retirado en la base deja de valer.
try {
    $consulta = $pdo->prepare('SELECT u.id, u.nombre, u.apellido, u.email, r.nombre AS nombre_rol
        FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id WHERE u.id = :id');
    $consulta->execute(['id' => $_SESSION['usuario_id']]);
    $usuario_sesion_bd = $consulta->fetch();
    $rol_actual = $usuario_sesion_bd ? rol_de_sesion($usuario_sesion_bd['nombre_rol']) : null;
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('No se pudo comprobar el acceso. Intentá nuevamente más tarde.');
}
if ($rol_actual === null) {
    $_SESSION = [];
    session_regenerate_id(true);
    header('Location: ' . url_tienda('login.php?error=acceso_denegado'), true, 303);
    exit;
}
if ($_SESSION['rol'] !== $rol_actual) {
    session_regenerate_id(true);
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$_SESSION['rol'] = $rol_actual;
$_SESSION['usuario_nombre'] = trim($usuario_sesion_bd['nombre'] . ' ' . $usuario_sesion_bd['apellido']);
$_SESSION['usuario_email'] = $usuario_sesion_bd['email'];

function verificar_rol(string $rol_requerido): void
{
    if ($_SESSION['rol'] !== $rol_requerido) {
        mostrar_error_acceso(403, 'Tu cuenta no tiene permiso para entrar a esta sección.');
    }
}
