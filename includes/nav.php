<nav>
    <a href="index.php"<?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo ' class="active"'; ?>>TF</a> |
    <a href="hombre.php"<?php if (basename($_SERVER['PHP_SELF']) == 'hombre.php') echo ' class="active"'; ?>>HOMBRE</a> |
    <a href="mujer.php"<?php if (basename($_SERVER['PHP_SELF']) == 'mujer.php') echo ' class="active"'; ?>>MUJER</a> |
    <a href="niños.php"<?php if (basename($_SERVER['PHP_SELF']) == 'niños.php') echo ' class="active"'; ?>>NIÑO/A</a> |
    <a href="descuentos.php"<?php if (basename($_SERVER['PHP_SELF']) == 'descuentos.php') echo ' class="active"'; ?>>DESCUENTOS</a> |
    <a href="registrarse.php"<?php if (basename($_SERVER['PHP_SELF']) == 'registrarse.php') echo ' class="active"'; ?>>REGISTRARSE</a> |
    <a href="carrito.php"<?php if (basename($_SERVER['PHP_SELF']) == 'carrito.php') echo ' class="active"'; ?>>CARRITO</a>
    <button id="btn-modo-oscuro">Modo oscuro</button>
</nav>