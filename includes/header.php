<?php
// Si la página no define estas variables, se usan valores por defecto
if (!isset($titulo_pagina)) {
    $titulo_pagina = "TF Clothing";
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
    <title><?= htmlspecialchars($titulo_pagina); ?></title>
    <meta name="description" content="<?= htmlspecialchars($descripcion_pagina); ?>">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body <?php if (isset($categoria)) { echo 'data-categoria="' . $categoria . '"'; } ?>>