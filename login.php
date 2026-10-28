<?php
require_once __DIR__ . '/config/autenticacion.php';
if (esta_autenticado()) {
    header('Location: ' . (es_administrador() ? 'admin/index.php' : 'cuenta.php'), true, 303);
    exit;
}
require_once __DIR__ . '/config/conexion.php';
$email = mb_strtolower(obtener_campo($_POST, 'email'), 'UTF-8');
$errores = [];
$aviso = $_SESSION['aviso_autenticacion'] ?? '';
unset($_SESSION['aviso_autenticacion']);
if (obtener_campo($_GET, 'error') === 'acceso_denegado') $aviso = 'Iniciá sesión para acceder a tu cuenta.';
if (obtener_campo($_GET, 'salida') === 'ok') $aviso = 'Cerraste tu sesión correctamente.';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    if (!csrf_valido($_POST)) {
        http_response_code(403);
        $errores[] = 'El formulario venció. Volvé a intentarlo.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email, 'UTF-8') > 254 || $password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
        http_response_code(422);
        $errores[] = 'Ingresá un correo válido y tu contraseña.';
    } else {
        $intentos = $_SESSION['intentos_login'] ?? ['cantidad' => 0, 'desde' => time()];
        if (time() - $intentos['desde'] >= 60) $intentos = ['cantidad' => 0, 'desde' => time()];
        if ($intentos['cantidad'] >= 5) {
            http_response_code(429);
            header('Retry-After: 60');
            $errores[] = 'Hubo varios intentos fallidos. Esperá un minuto y volvé a intentar.';
        } else {
            try {
                $consulta = $pdo->prepare('SELECT u.id, u.nombre, u.apellido, u.email, u.password, r.nombre AS nombre_rol
                    FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id WHERE u.email = :email');
                $consulta->execute(['email' => $email]);
                $cuenta = $consulta->fetch();
                // El hash de reemplazo mantiene la verificación cuando el correo no existe.
                $hash = $cuenta['password'] ?? '$2y$12$0QR4G.MWQOiJ7SscBQNUd.z7/gmMUw9nQVZkO1jt7ZHBLCvy6Rxou';
                $coincide = password_verify($password, $hash);
                $rol = $cuenta ? rol_de_sesion($cuenta['nombre_rol']) : null;
                // Los hashes de práctica publicados en el TP6 no habilitan una cuenta real.
                $es_demo = hash_equals('$2y$10$Kd3k7ptfbHgu.NUNbOX01OcawF.H0ZxEq6Ao1SN8q9IH6krDscLHy', $hash);
                if (!$cuenta || !$coincide || $rol === null || $es_demo) {
                    $intentos['cantidad']++;
                    $_SESSION['intentos_login'] = $intentos;
                    http_response_code(401);
                    $errores[] = 'El correo o la contraseña son incorrectos.';
                } else {
                    $informacion_hash = password_get_info($hash);
                    if ($informacion_hash['algoName'] === 'bcrypt' && ($informacion_hash['options']['cost'] ?? 0) < 12) {
                        $nuevo_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                        $consulta = $pdo->prepare('UPDATE usuarios SET password = :nuevo WHERE id = :id AND password = :anterior');
                        $consulta->execute(['nuevo' => $nuevo_hash, 'id' => $cuenta['id'], 'anterior' => $hash]);
                    }
                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = (int) $cuenta['id'];
                    $_SESSION['usuario_nombre'] = trim($cuenta['nombre'] . ' ' . $cuenta['apellido']);
                    $_SESSION['usuario_email'] = $cuenta['email'];
                    $_SESSION['rol'] = $rol;
                    $_SESSION['ultima_actividad'] = time();
                    $_SESSION['csrf'] = bin2hex(random_bytes(32));
                    unset($_SESSION['intentos_login'], $_SESSION['compra'], $_SESSION['pedido_confirmado']);
                    header('Location: ' . ($rol === 'admin' ? 'admin/index.php' : 'cuenta.php'), true, 303);
                    exit;
                }
            } catch (Throwable $e) {
                error_log($e->getMessage());
                http_response_code(503);
                $errores[] = 'No pudimos iniciar la sesión. Intentá nuevamente más tarde.';
            }
        }
    }
}
$titulo_pagina = 'Iniciar sesión | TF Clothing';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-acceso">
    <h1>Iniciá sesión</h1>
    <p>Ingresá a tu cuenta de TF para consultar tus pedidos.</p>
    <?php if ($aviso !== ''): ?><p class="aviso" role="status"><?= escapar($aviso) ?></p><?php endif; ?>
    <?php if ($errores): ?><div class="aviso" role="alert"><ul><?php foreach ($errores as $error): ?><li><?= escapar($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form class="form-acceso" method="post" action="login.php" novalidate>
        <input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
        <label for="email">Correo electrónico</label>
        <input id="email" name="email" type="email" autocomplete="username" maxlength="254" value="<?= escapar($email) ?>" required>
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" autocomplete="current-password" maxlength="72" required>
        <button type="submit">Iniciar sesión</button>
    </form>
    <p>¿Todavía no tenés cuenta? <a href="registro.php">Creá tu cuenta</a></p>
    <p><a href="clase5/buscar.php">Seguir viendo la colección</a></p>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
