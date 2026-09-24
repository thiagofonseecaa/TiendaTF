<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesorios - TF</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body data-categoria="accesorios">
    <h1>Accesorios</h1>

    <div class="galeria">
        <button id="btn-anterior">&#8592; Anterior</button>
        <div>
            <img id="galeria-imagen" src="imagenes/gorras.png" alt="Producto accesorio">
            <p id="galeria-nombre">Gorras</p>
        </div>
        <button id="btn-siguiente">Siguiente &#8594;</button>
    </div>

    <div id="modal-imagen" class="modal-oculto">
        <span id="cerrar-modal">&times;</span>
        <img id="modal-imagen-grande" src="" alt="Imagen ampliada">
    </div>

    <hr>
    <footer>
        <p><a href="index.php">Volver al inicio</a></p>
        <p>TF - Todos los derechos reservados</p>
    </footer>

    <script src="js/script.js"></script>
    <script src="js/categoria.js"></script>
</body>
</html>