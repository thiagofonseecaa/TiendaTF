USE tf_tienda_tp6;

-- Conserva los productos de la colección anterior sin cambiar precios ya editados.
INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-JEA-H01', 'Jean recto de hombre', c.id, 54900.00, 'clase5/imagenes/jean-recto.png', 1
FROM categorias c WHERE c.nombre = 'Hombre'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-JEA-H01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-BUZ-H01', 'Buzo negro con cierre de hombre', c.id, 42900.00, 'clase5/imagenes/buzo-infantil.png', 1
FROM categorias c WHERE c.nombre = 'Hombre'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-BUZ-H01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-PAN-M01', 'Pantalón jogger de mujer', c.id, 38900.00, 'clase5/imagenes/pantalon-mujer.png', 1
FROM categorias c WHERE c.nombre = 'Mujer'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-PAN-M01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-BUZ-M01', 'Buzo negro de mujer', c.id, 39900.00, 'clase5/imagenes/buzo-mujer.png', 1
FROM categorias c WHERE c.nombre = 'Mujer'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-BUZ-M01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-PAN-N01', 'Pantalón jogger infantil', c.id, 24900.00, 'clase5/imagenes/pantalon-infantil.png', 1
FROM categorias c WHERE c.nombre = 'Niño/a'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-PAN-N01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-BUZ-N01', 'Buzo gris infantil', c.id, 28900.00, 'clase5/imagenes/buzo-clasico.png', 1
FROM categorias c WHERE c.nombre = 'Niño/a'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-BUZ-N01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-CIN-01', 'Cinto', c.id, 12900.00, 'imagenes/cintos.png', 1
FROM categorias c WHERE c.nombre = 'Accesorios'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-CIN-01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-ANI-01', 'Anillo', c.id, 8900.00, 'imagenes/anillo.png', 1
FROM categorias c WHERE c.nombre = 'Accesorios'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-ANI-01');

INSERT INTO productos (sku, nombre, categoria_id, precio, imagen, activo)
SELECT 'TF-REL-01', 'Reloj', c.id, 29900.00, 'imagenes/reloj.png', 1
FROM categorias c WHERE c.nombre = 'Accesorios'
AND NOT EXISTS (SELECT 1 FROM productos WHERE sku = 'TF-REL-01');

-- Una fila por cada talle, sin listas guardadas dentro de una columna.
INSERT INTO producto_talles (producto_id, talle)
SELECT p.id, t.talle FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id
CROSS JOIN (
    SELECT 'S' AS talle UNION ALL SELECT 'M' UNION ALL SELECT 'L' UNION ALL SELECT 'XL'
    UNION ALL SELECT '6' UNION ALL SELECT '8' UNION ALL SELECT '10' UNION ALL SELECT '12' UNION ALL SELECT 'Único'
) t
WHERE NOT EXISTS (SELECT 1 FROM producto_talles pt WHERE pt.producto_id = p.id)
AND ((c.nombre = 'Niño/a' AND t.talle IN ('6','8','10','12'))
 OR ((c.nombre = 'Accesorios' OR LOWER(p.nombre) LIKE '%gorra%') AND t.talle = 'Único')
 OR (c.nombre IN ('Hombre','Mujer') AND LOWER(p.nombre) NOT LIKE '%gorra%' AND t.talle IN ('S','M','L','XL')));
