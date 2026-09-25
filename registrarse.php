<?php
$titulo_pagina = "Registrarse | TF Clothing";
$descripcion_pagina = "Completá el formulario de registro de TF Clothing.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Registrarse</h1>

    <form action="/registrar" method="post" id="formRegistro" novalidate>
        <p>
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre"
                   placeholder="Ingresá tu nombre"
                   minlength="3" maxlength="40" required>
            <br>
            <span id="error-nombre" style="color:red; font-size:0.85rem;"></span>
        </p>

        <p>
            <label for="email">Correo electrónico:</label><br>
            <input type="email" id="email" name="email"
                   placeholder="ejemplo@correo.com" required>
            <br>
            <span id="error-email" style="color:red; font-size:0.85rem;"></span>
        </p>

        <p>
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password"
                   placeholder="Mínimo 8 caracteres"
                   minlength="8" required>
        </p>

        <p>
            <label for="dni">DNI:</label><br>
            <input type="text" id="dni" name="dni"
                   placeholder="Ej: 40123456"
                   pattern="[0-9]{7,8}"
                   title="Debe tener 7 u 8 números, sin puntos" required>
        </p>

        <p>
            <label for="telefono">Teléfono:</label><br>
            <input type="number" id="telefono" name="telefono"
                   placeholder="Sin 0 ni 15"
                   min="1000000" max="9999999999">
        </p>

        <p>
            <label for="fecha_nacimiento">Fecha de nacimiento:</label><br>
            <input type="date" id="fecha_nacimiento"
                   name="fecha_nacimiento" required>
        </p>

        <p>
            <label for="talle">Talle habitual:</label><br>
            <select id="talle" name="talle" required>
                <option value="">Seleccioná una opción</option>
                <option value="s">S</option>
                <option value="m">M</option>
                <option value="l">L</option>
                <option value="xl">XL</option>
            </select>
        </p>

        <fieldset>
            <legend>Género</legend>
            <label>
                <input type="radio" name="genero" value="femenino">
                Femenino
            </label><br>
            <label>
                <input type="radio" name="genero" value="masculino">
                Masculino
            </label><br>
            <label>
                <input type="radio" name="genero" value="otro">
                Otro
            </label>
        </fieldset>

        <p>
            <label for="direccion">Dirección de envío:</label><br>
            <textarea id="direccion" name="direccion"
                      rows="3" cols="40"
                      placeholder="Calle, número, ciudad"></textarea>
        </p>

        <p>
            <label for="newsletter">
                <input type="checkbox" id="newsletter"
                       name="newsletter" value="si">
                Quiero recibir novedades y descuentos por correo
            </label>
        </p>

        <p>
            <button type="submit">Registrarme</button>
            <button type="reset">Borrar</button>
        </p>

        <div id="resumen"
             style="display:none; margin-top:20px; padding:15px; border:1px solid #ccc;">
            <h3>Resumen de tus datos</h3>
            <p><strong>Nombre:</strong> <span id="resumen-nombre"></span></p>
            <p><strong>Email:</strong> <span id="resumen-email"></span></p>
            <p><strong>Talle:</strong> <span id="resumen-talle"></span></p>
        </div>
    </form>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>