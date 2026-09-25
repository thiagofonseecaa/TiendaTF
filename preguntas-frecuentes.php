<?php
$titulo_pagina = "Preguntas Frecuentes | TF Clothing";
$descripcion_pagina = "Consultá las respuestas sobre envíos, cambios y medios de pago de TF Clothing.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Preguntas Frecuentes</h1>

    <div class="acordeon">
        <button type="button" class="acordeon-pregunta">
            ¿Cuánto demora el envío?
        </button>
        <p class="acordeon-respuesta">
            Entre 3 y 7 días hábiles según la localidad.
        </p>

        <button type="button" class="acordeon-pregunta">
            ¿Puedo cambiar un producto?
        </button>
        <p class="acordeon-respuesta">
            Sí, dentro de los 10 días de recibido el pedido.
        </p>

        <button type="button" class="acordeon-pregunta">
            ¿Qué medios de pago aceptan?
        </button>
        <p class="acordeon-respuesta">
            Tarjeta de crédito, débito y Mercado Pago.
        </p>
    </div>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>