<footer>
    <h3><a href="blog.php">Novedades</a></h3>
    <h3><a href="ayuda.php">Ayuda</a></h3>
    <h3><a href="redes-sociales.php">Redes Sociales</a></h3>
    <h3><a href="quienes-somos.php">Quiénes somos</a></h3>
    <h3><a href="preguntas-frecuentes.php">Preguntas Frecuentes</a></h3>
    <h3><a href="guia-de-talles.php">Guía de talles</a></h3>
    <h3><a href="curso.php">Curso: Styling Urbano</a></h3>
    <h3><a href="cv.php">Trabajá con nosotros</a></h3>
    <p>© <?= date("Y"); ?> TF Clothing - Todos los derechos reservados</p>
</footer>

<script src="js/script.js"></script>
<?php if (isset($categoria)) { ?>
    <script src="js/categoria.js"></script>
<?php } ?>
</body>
</html>