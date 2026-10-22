<?php
require_once __DIR__ . '/env.php';

$host = $configuracion['DB_HOST'] ?? '127.0.0.1';
$puerto = $configuracion['DB_PORT'] ?? '3306';
$base_datos = $configuracion['DB_NAME'] ?? 'tf_tienda_tp6';
$usuario = $configuracion['DB_USER'] ?? 'root';
$clave = $configuracion['DB_PASS'] ?? '';

$dsn = "mysql:host=$host;port=$puerto;dbname=$base_datos;charset=utf8mb4";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $clave, $opciones);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit('No se pudo conectar con la base de datos. Revisá que MySQL esté iniciado y la configuración sea correcta.');
}
