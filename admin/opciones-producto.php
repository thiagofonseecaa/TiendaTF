<?php
$talles_disponibles = ['S', 'M', 'L', 'XL', '6', '8', '10', '12', 'Único'];
$imagenes_disponibles = [
    'clase5/imagenes/remera-clasica.png' => 'Remera azul',
    'clase5/imagenes/jean-recto.png' => 'Jean recto',
    'clase5/imagenes/buzo-infantil.png' => 'Buzo con cierre',
    'clase5/imagenes/remera-mujer.png' => 'Remera blanca de mujer',
    'clase5/imagenes/pantalon-mujer.png' => 'Pantalón de mujer',
    'clase5/imagenes/buzo-mujer.png' => 'Buzo de mujer',
    'clase5/imagenes/remera-infantil.png' => 'Remera infantil',
    'clase5/imagenes/pantalon-infantil.png' => 'Pantalón infantil',
    'clase5/imagenes/buzo-clasico.png' => 'Buzo gris',
    'imagenes/gorras.png' => 'Gorra', 'imagenes/cintos.png' => 'Cinto',
    'imagenes/anillo.png' => 'Anillo', 'imagenes/reloj.png' => 'Reloj',
];

function talles_recibidos(array $datos): array
{
    global $talles_disponibles;
    $talles = $datos['talles'] ?? [];
    if (!is_array($talles) || !$talles) return [];
    foreach ($talles as $talle) {
        if (!is_string($talle) || !in_array($talle, $talles_disponibles, true)) return [];
    }
    return array_values(array_unique($talles));
}

function guardar_talles(PDO $pdo, int $id, array $talles): void
{
    $borrar = $pdo->prepare('DELETE FROM producto_talles WHERE producto_id = :id');
    $borrar->execute(['id' => $id]);
    $insertar = $pdo->prepare('INSERT INTO producto_talles (producto_id, talle) VALUES (:id, :talle)');
    foreach ($talles as $talle) $insertar->execute(['id' => $id, 'talle' => $talle]);
}
