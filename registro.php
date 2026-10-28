<?php
require_once __DIR__ . '/config/autenticacion.php';
if (esta_autenticado()) {
    header('Location: cuenta.php', true, 303);
    exit;
}
require_once __DIR__ . '/config/conexion.php';
$nombre = obtener_campo($_POST, 'nombre');
$apellido = obtener_campo($_POST, 'apellido');
$email = mb_strtolower(obtener_campo($_POST, 'email'), 'UTF-8');
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $confirmacion = is_string($_POST['confirmacion'] ?? null) ? $_POST['confirmacion'] : '';
    if (!csrf_valido($_POST)) {
        http_response_code(403);
        $errores[] = 'El formulario venció. Volvé a intentarlo.';
    }
    foreach (['Nombre' => $nombre, 'Apellido' => $apellido] as $campo => $valor) {
        if ($valor === '' || mb_strlen($valor, 'UTF-8') > 100 || !preg_match("/\A[\p{L}\p{M}][\p{L}\p{M} '\-]*\z/u", $valor)) {
            $errores[] = $campo . ': ingresá hasta 100 caracteres, usando letras, espacios, apóstrofes o guiones.';
        }
    }
    if (mb_strlen($email, 'UTF-8') > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Ingresá un correo electrónico válido.';
    }
    if (mb_strlen($password, 'UTF-8') < 15) {
        $errores[] = 'Usá una contraseña de al menos 15 caracteres. Podés elegir una frase fácil de recordar.';
    }
    // Bcrypt admite hasta 72 bytes. Se rechaza el exceso para evitar truncamientos.
    if (strlen($password) > 72 || str_contains($password, "\0")) {
        $errores[] = 'La contraseña es demasiado larga o contiene caracteres no admitidos. Elegí otra frase.';
    }
    if ($password !== $confirmacion) $errores[] = 'Las contraseñas no coinciden.';
    if ($errores && http_response_code() !== 403) http_response_code(422);

    if (!$errores) {
        try {
            $consulta = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
            $consulta->execute(['email' => $email]);
            if ($consulta->fetch()) {
                http_response_code(422);
                $errores[] = 'Ya existe una cuenta con ese correo. Podés iniciar sesión.';
            } else {
                $consulta = $pdo->prepare('SELECT id FROM roles WHERE nombre = :nombre');
                $consulta->execute(['nombre' => 'cliente']);
                $rol_id = $consulta->fetchColumn();
                if (!$rol_id) throw new RuntimeException('Falta el rol cliente en la base.');
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $consulta = $pdo->prepare('INSERT INTO usuarios (rol_id, nombre, apellido, email, password)
                    VALUES (:rol_id, :nombre, :apellido, :email, :password)');
                $consulta->execute(['rol_id' => $rol_id, 'nombre' => $nombre, 'apellido' => $apellido, 'email' => $email, 'password' => $hash]);
                $_SESSION['aviso_autenticacion'] = 'Tu cuenta ya está creada. Iniciá sesión con tu correo y contraseña.';
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
                header('Location: login.php', true, 303);
                exit;
            }
        } catch (Throwable $e) {
            if ($e instanceof PDOException && (int) ($e->errorInfo[1] ?? 0) === 1062) {
                http_response_code(422);
                $errores[] = 'Ya existe una cuenta con ese correo. Podés iniciar sesión.';
            } else {
                error_log($e->getMessage());
                http_response_code(503);
                $errores[] = 'No pudimos crear la cuenta. Intentá nuevamente más tarde.';
            }
        }
    }
}
$titulo_pagina = 'Crear cuenta | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-acceso">
    <h1>Creá tu cuenta</h1>
    <p>Guardá tus pedidos y tené tus compras de TF en un solo lugar.</p>
    <?php if ($errores): ?><div class="aviso" role="alert"><ul><?php foreach ($errores as $error): ?><li><?= escapar($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form class="form-acceso" method="post" action="registro.php" novalidate>
        <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
        <div class="campos-cuenta">
            <div><label for="nombre">Nombre</label><input id="nombre" name="nombre" autocomplete="given-name" maxlength="100" value="<?= escapar($nombre) ?>" required></div>
            <div><label for="apellido">Apellido</label><input id="apellido" name="apellido" autocomplete="family-name" maxlength="100" value="<?= escapar($apellido) ?>" required></div>
        </div>
        <label for="email">Correo electrónico</label>
        <input id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= escapar($email) ?>" required>
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" autocomplete="new-password" minlength="15" maxlength="72" aria-describedby="ayuda-password" required>
        <p id="ayuda-password" class="nota-precios">Usá una frase de al menos 15 caracteres. No uses tu nombre ni datos fáciles de adivinar.</p>
        <label for="confirmacion">Repetí la contraseña</label>
        <input id="confirmacion" name="confirmacion" type="password" autocomplete="new-password" minlength="15" maxlength="72" required>
        <button type="submit">Crear cuenta</button>
    </form>
    <p>¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a></p>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
