<?php
require_once __DIR__ . '/clase5/tienda.php';
$titulo_pagina = 'Trabajá con nosotros | TF Clothing';
$nombre = obtener_campo($_POST, 'nombre');
$correo = obtener_campo($_POST, 'correo');
$area = obtener_campo($_POST, 'area');
$areas = ['Atención al cliente', 'Productos y catálogo', 'Comunicación y redes'];
$errores = [];
$enviado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], obtener_campo($_POST, 'csrf'))) $errores[] = 'Recargá la página e intentá nuevamente.';
    if ($nombre === '' || mb_strlen($nombre) > 100) $errores[] = 'Ingresá tu nombre (hasta 100 caracteres).';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($correo) > 254) $errores[] = 'Ingresá un correo válido.';
    if (!in_array($area, $areas, true)) $errores[] = 'Elegí un área de interés.';
    if (obtener_campo($_POST, 'consentimiento') !== 'si') $errores[] = 'Aceptá el uso de tus datos para postularte.';
    $archivo = $_FILES['cv'] ?? [];
    if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $errores[] = 'Adjuntá tu CV en PDF, de hasta 2 MB.';
    } elseif ($archivo['size'] > 2 * 1024 * 1024 || $archivo['size'] === 0) {
        $errores[] = 'El PDF debe pesar entre 1 byte y 2 MB.';
    } elseif (strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION)) !== 'pdf'
        || (new finfo(FILEINFO_MIME_TYPE))->file($archivo['tmp_name']) !== 'application/pdf'
        || file_get_contents($archivo['tmp_name'], false, null, 0, 5) !== '%PDF-') {
        $errores[] = 'El archivo debe ser un PDF válido.';
    }
    if (empty($errores)) {
        $carpeta = dirname(__DIR__, 2) . '/tf-postulaciones';
        $codigo = bin2hex(random_bytes(12));
        if (!is_dir($carpeta) && !mkdir($carpeta, 0700, true)) {
            $errores[] = 'No pudimos guardar tu CV. Intentá más tarde.';
        } elseif (move_uploaded_file($archivo['tmp_name'], $carpeta . '/' . $codigo . '.pdf')) {
            $datos = json_encode(['nombre' => $nombre, 'correo' => $correo, 'area' => $area, 'fecha' => date('c')], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            if (file_put_contents($carpeta . '/' . $codigo . '.json', $datos) === false) {
                unlink($carpeta . '/' . $codigo . '.pdf');
                $errores[] = 'No pudimos guardar la postulación. Intentá más tarde.';
            } else {
                $_SESSION['cv_recibido'] = true;
                header('Location: cv.php', true, 303);
                exit;
            }
        } else {
            $errores[] = 'No pudimos guardar tu CV. Intentá más tarde.';
        }
    }
}
$enviado = $_SESSION['cv_recibido'] ?? false;
unset($_SESSION['cv_recibido']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>
<main id="main-content" class="pagina-info">
<h1>Trabajá con nosotros</h1>
<p class="intro-info">Nos interesa conocer personas con ganas de aprender y aportar al equipo.</p>
<div class="empleo-grid">
<section class="bloque-info"><h2>Somos TF Clothing</h2><p>TF es una propuesta de ropa urbana para hombre, mujer y niño/a, con accesorios para completar cada conjunto. Nuestro estilo parte de prendas cómodas y combinaciones sencillas para todos los días.</p><h2>Qué valoramos</h2><ul><li>Buen trato y atención a las personas.</li><li>Responsabilidad y organización.</li><li>Ganas de aprender y trabajar en equipo.</li></ul><p>Podés compartir tu CV para futuras oportunidades en atención al cliente, catálogo o comunicación.</p></section>
<section class="bloque-info"><h2>Dejanos tu CV</h2>
<?php if ($enviado): ?><p class="aviso" role="status">Recibimos tu CV y tus datos. Gracias por tu interés en TF.</p><?php endif; ?>
<?php if ($errores): ?><div class="aviso" role="alert"><ul><?php foreach ($errores as $error): ?><li><?= escapar($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="form-cv" action="cv.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf" value="<?= escapar($_SESSION['csrf']) ?>">
<label for="nombre-cv">Nombre y apellido</label><input id="nombre-cv" name="nombre" maxlength="100" value="<?= escapar($nombre) ?>" autocomplete="name" required>
<label for="correo-cv">Correo electrónico</label><input id="correo-cv" name="correo" type="email" maxlength="254" value="<?= escapar($correo) ?>" autocomplete="email" required>
<label for="area-cv">Área de interés</label><select id="area-cv" name="area" required><option value="">Seleccioná un área</option><?php foreach ($areas as $opcion): ?><option value="<?= escapar($opcion) ?>"<?= $area === $opcion ? ' selected' : '' ?>><?= escapar($opcion) ?></option><?php endforeach; ?></select>
<label for="archivo-cv">Currículum en PDF</label><input id="archivo-cv" type="file" name="cv" accept=".pdf,application/pdf" aria-describedby="ayuda-cv" required>
<p id="ayuda-cv" class="nota-precios">Máximo 2 MB. Si hay un error, tendrás que seleccionar el archivo nuevamente.</p>
<label class="consentimiento-cv"><input type="checkbox" name="consentimiento" value="si" required> Acepto que TF guarde mis datos y mi CV para evaluar mi perfil laboral.</label>
<button type="submit">Enviar CV</button>
</form>
</section></div>
<p><a href="index.php">Volver al inicio</a></p>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
