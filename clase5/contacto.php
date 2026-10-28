<?php
require_once __DIR__ . '/tienda.php';
$base_sitio = '../';

$datos = [
    'nombre' => '',
    'email' => '',
    'mensaje' => ''
];

$errores = [];
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos['nombre'] = obtener_campo($_POST, 'nombre');
    $datos['email'] = obtener_campo($_POST, 'email');
    $datos['mensaje'] = obtener_campo($_POST, 'mensaje');

    if ($datos['nombre'] === '') {
        $errores[] = 'Ingresá tu nombre.';
    } elseif (mb_strlen($datos['nombre']) > 80) {
        $errores[] = 'El nombre no puede superar los 80 caracteres.';
    }

    if ($datos['email'] === '') {
        $errores[] = 'Ingresá tu correo electrónico.';
    } elseif (
        strlen($datos['email']) > 254 ||
        !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $errores[] = 'Ingresá un correo electrónico válido.';
    }

    if ($datos['mensaje'] === '') {
        $errores[] = 'Escribí un mensaje.';
    } elseif (mb_strlen($datos['mensaje']) > 2000) {
        $errores[] = 'El mensaje no puede superar los 2000 caracteres.';
    }

    $exito = empty($errores);
}

$titulo_pagina = 'Contacto | TF Clothing';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/nav.php';
?>

    <main id="main-content">
        <h1>Contacto</h1>
        <p>Dejanos tu consulta sobre la tienda.</p>

        <?php if (!empty($errores)): ?>
            <div role="alert">
                <h2>Revisá los siguientes datos</h2>
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= escapar($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($exito): ?>
            <div role="status">
                <h2>Consulta procesada correctamente</h2>
                <p>Estos son los datos que recibió el servidor:</p>
                <p>
                    <strong>Nombre:</strong>
                    <?= escapar($datos['nombre']) ?>
                </p>
                <p>
                    <strong>Correo:</strong>
                    <?= escapar($datos['email']) ?>
                </p>
                <p>
                    <strong>Mensaje:</strong><br>
                    <?= nl2br(escapar($datos['mensaje'])) ?>
                </p>
            </div>
        <?php endif; ?>

        <form action="contacto.php" method="post" novalidate>
            <p>
                <label for="nombre">Nombre:</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    maxlength="80"
                    value="<?= escapar($datos['nombre']) ?>"
                    required
                >
            </p>

            <p>
                <label for="email">Correo electrónico:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="254"
                    value="<?= escapar($datos['email']) ?>"
                    required
                >
            </p>

            <p>
                <label for="mensaje">Mensaje:</label>
                <textarea
                    id="mensaje"
                    name="mensaje"
                    rows="5"
                    maxlength="2000"
                    required
                ><?= escapar($datos['mensaje']) ?></textarea>
            </p>

            <p>
                <button type="submit">Enviar consulta</button>
            </p>
        </form>

        <p><a href="../index.php">Volver a la tienda</a></p>
    </main>

<?php require __DIR__ . '/../includes/footer.php'; ?>
