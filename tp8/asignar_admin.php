<?php
// Esta herramienta se usa en la terminal del dueño; no se puede ejecutar por HTTP.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/../config/conexion.php';
$email = strtolower(trim($argv[1] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Uso: php tp8/asignar_admin.php correo-de-tu-cuenta\nPrimero creá tu cuenta desde registro.php.\n");
    exit(1);
}
$consulta = $pdo->prepare('SELECT id FROM roles WHERE nombre = :nombre');
$consulta->execute(['nombre' => 'administrador']);
$rol_id = $consulta->fetchColumn();
if (!$rol_id) {
    fwrite(STDERR, "No se encontró el rol administrador. Revisá la base del TP6.\n");
    exit(1);
}
$consulta = $pdo->prepare('SELECT id, password FROM usuarios WHERE email = :email');
$consulta->execute(['email' => $email]);
$cuenta = $consulta->fetch();
if (!$cuenta || hash_equals('$2y$10$Kd3k7ptfbHgu.NUNbOX01OcawF.H0ZxEq6Ao1SN8q9IH6krDscLHy', $cuenta['password'])) {
    fwrite(STDERR, "Registrá primero una cuenta propia. Las cuentas ficticias del TP6 se conservan como datos de prueba.\n");
    exit(1);
}
$consulta = $pdo->prepare('UPDATE usuarios SET rol_id = :rol_id WHERE id = :id');
$consulta->execute(['rol_id' => $rol_id, 'id' => $cuenta['id']]);
echo "Cuenta configurada como administradora. Iniciá sesión con tu correo y la contraseña que elegiste.\n";
