<footer>
    <h3><a href="<?= escapar($base_sitio) ?>blog.php">Novedades</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>ayuda.php">Ayuda</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>redes-sociales.php">Redes Sociales</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>quienes-somos.php">Quiénes somos</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>preguntas-frecuentes.php">Preguntas Frecuentes</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>guia-de-talles.php">Guía de talles</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>curso.php">Curso: Styling Urbano</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>cv.php">Trabajá con nosotros</a></h3>
    <h3><a href="<?= escapar($base_sitio) ?>admin/index.php">Gestión de productos</a></h3>
<p>© <?= date("Y"); ?> <?= htmlspecialchars($nombre_sitio, ENT_QUOTES, 'UTF-8'); ?> - Todos los derechos reservados</p>
</footer>

<script src="<?= escapar($base_sitio) ?>js/script.js"></script>
<?php if (isset($categoria)) { ?>
    <script src="<?= escapar($base_sitio) ?>js/categoria.js"></script>
<?php } ?>
</body>
</html>