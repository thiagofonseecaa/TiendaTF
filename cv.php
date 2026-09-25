<?php
$titulo_pagina = "Trabajá con nosotros | TF Clothing";
$descripcion_pagina = "Conocé las búsquedas laborales de TF Clothing y envianos tu CV para formar parte del equipo.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

<hr>

<main id="main-content">
    <h1>Trabajá con nosotros</h1>

    <div class="galeria-pagina">
        <img src="imagenes/trabajaconnosotros.png"
             alt="Trabajá con nosotros" class="img-pagina">
    </div>

    <p>En TF estamos siempre buscando sumar gente con actitud, ganas de aprender y pasión por la moda urbana. Si te interesa formar parte del equipo, esta es tu oportunidad.</p>

    <h2>Búsquedas activas</h2>

    <h3>Vendedor/a de local - Salta Centro</h3>
    <p>Buscamos alguien con buena predisposición para atención al cliente, disponibilidad full time y ganas de crecer dentro de la marca.</p>

    <h3>Community Manager</h3>
    <p>Encargado/a de redes sociales, contenido y estrategia digital de la marca.</p>

    <h2>¿Cómo postularte?</h2>
    <p>Envianos tu CV actualizado a <a href="mailto:trabajo@tf.com">trabajo@tf.com</a>, contándonos brevemente por qué te gustaría sumarte al equipo TF.</p>
    <p>Revisamos todas las postulaciones y nos contactamos con los perfiles que más se ajusten a la búsqueda.</p>

    <div class="contenedor-boton">
        <a href="mailto:trabajo@tf.com" class="boton-cv">Enviar CV</a>
    </div>

    <p><a href="index.php">Volver al inicio</a></p>
</main>

<hr>

<?php require_once __DIR__ . '/includes/footer.php'; ?>