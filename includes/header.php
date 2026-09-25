<?php
require_once __DIR__ . '/../config/env.php';

if (!isset($titulo_pagina)) {
    $titulo_pagina = $nombre_sitio;
}

if (!isset($descripcion_pagina)) {
    $descripcion_pagina = "Tienda de ropa urbana y streetwear en Salta. Envíos a todo el país.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo_pagina, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?= htmlspecialchars($descripcion_pagina, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body<?php if (isset($categoria)) { ?>
    data-categoria="<?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8'); ?>"
<?php } ?>>