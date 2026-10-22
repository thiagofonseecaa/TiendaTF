<?php
$productos = [
    ['id' => '1', 'precio' => 24900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Remera azul de hombre', 'imagen' => 'remera-clasica.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'remera remeras algodón lisa'],
    ['id' => '2', 'precio' => 54900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Jean recto de hombre', 'imagen' => 'jean-recto.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'pantalon pantalón pantalones jean jeans'],
    ['id' => '3', 'precio' => 42900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Buzo negro con cierre de hombre', 'imagen' => 'buzo-infantil.png', 'categoria' => 'Hombre', 'seccion' => 'hombre.php', 'terminos' => 'buzo buzos elegante'],
    ['id' => '4', 'precio' => 22900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Remera blanca de mujer', 'imagen' => 'remera-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'remera remeras algodón'],
    ['id' => '5', 'precio' => 38900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Pantalón jogger de mujer', 'imagen' => 'pantalon-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'pantalon pantalones jogger'],
    ['id' => '6', 'precio' => 39900, 'talles' => ['S', 'M', 'L', 'XL'], 'nombre' => 'Buzo negro de mujer', 'imagen' => 'buzo-mujer.png', 'categoria' => 'Mujer', 'seccion' => 'mujer.php', 'terminos' => 'buzo buzos'],
    ['id' => '7', 'precio' => 16900, 'talles' => ['6', '8', '10', '12'], 'nombre' => 'Remera blanca infantil', 'imagen' => 'remera-infantil.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'remera remeras niño niña niños niñas nino nina ninos ninas'],
    ['id' => '8', 'precio' => 24900, 'talles' => ['6', '8', '10', '12'], 'nombre' => 'Pantalón jogger infantil', 'imagen' => 'pantalon-infantil.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'pantalon pantalones niño niña niños niñas nino nina ninos ninas'],
    ['id' => '9', 'precio' => 28900, 'talles' => ['6', '8', '10', '12'], 'nombre' => 'Buzo gris infantil', 'imagen' => 'buzo-clasico.png', 'categoria' => 'Niño/a', 'seccion' => 'ni%C3%B1os.php', 'terminos' => 'buzo buzos niño niña niños niñas nino nina ninos ninas'],
    ['id' => '10', 'precio' => 14900, 'talles' => ['Único'], 'nombre' => 'Gorra', 'imagen' => 'gorras.png', 'carpeta' => 'imagenes/', 'categoria' => 'Accesorios', 'seccion' => 'accesorios.php', 'terminos' => 'gorra gorras'],
    ['id' => '11', 'precio' => 12900, 'talles' => ['Único'], 'nombre' => 'Cinto', 'imagen' => 'cintos.png', 'carpeta' => 'imagenes/', 'categoria' => 'Accesorios', 'seccion' => 'accesorios.php', 'terminos' => 'cinto cintos cinturón cinturones'],
    ['id' => '12', 'precio' => 8900, 'talles' => ['Único'], 'nombre' => 'Anillo', 'imagen' => 'anillo.png', 'carpeta' => 'imagenes/', 'categoria' => 'Accesorios', 'seccion' => 'accesorios.php', 'terminos' => 'anillo anillos'],
    ['id' => '13', 'precio' => 29900, 'talles' => ['Único'], 'nombre' => 'Reloj', 'imagen' => 'reloj.png', 'carpeta' => 'imagenes/', 'categoria' => 'Accesorios', 'seccion' => 'accesorios.php', 'terminos' => 'reloj relojes']
];

function precio_ars(int $precio): string
{
    return '$ ' . number_format($precio, 0, ',', '.');
}
