USE tf_tienda_tp6;
SET NAMES utf8mb4;

START TRANSACTION;

INSERT INTO roles (nombre) VALUES ('cliente');
INSERT INTO roles (nombre) VALUES ('administrador');
INSERT INTO categorias (nombre) VALUES ('Hombre');
INSERT INTO categorias (nombre) VALUES ('Mujer');
INSERT INTO categorias (nombre) VALUES ('Niño/a');
INSERT INTO categorias (nombre) VALUES ('Accesorios');
INSERT INTO estados_pedido (nombre) VALUES ('pendiente'), ('confirmado'), ('cancelado');

SET @rol_cliente = (SELECT id FROM roles WHERE nombre = 'cliente');
SET @rol_admin = (SELECT id FROM roles WHERE nombre = 'administrador');
SET @cat_hombre = (SELECT id FROM categorias WHERE nombre = 'Hombre');
SET @cat_mujer = (SELECT id FROM categorias WHERE nombre = 'Mujer');
SET @cat_infantil = (SELECT id FROM categorias WHERE nombre = 'Niño/a');
SET @cat_accesorios = (SELECT id FROM categorias WHERE nombre = 'Accesorios');
SET @estado_pendiente = (SELECT id FROM estados_pedido WHERE nombre = 'pendiente');

INSERT INTO usuarios (rol_id, nombre, apellido, email, password) VALUES
(@rol_cliente, 'Lucas', 'Medina', 'lucas.medina@example.com', '$2y$10$Kd3k7ptfbHgu.NUNbOX01OcawF.H0ZxEq6Ao1SN8q9IH6krDscLHy'),
(@rol_admin, 'Martina', 'Ríos', 'martina.rios@example.com', '$2y$10$Kd3k7ptfbHgu.NUNbOX01OcawF.H0ZxEq6Ao1SN8q9IH6krDscLHy');

INSERT INTO productos (categoria_id, sku, nombre, descripcion, precio, imagen) VALUES
(@cat_hombre, 'TF-REM-H01', 'Remera azul de hombre', 'Remera lisa de uso diario.', 24900.00, 'clase5/imagenes/remera-clasica.png');

INSERT INTO productos (categoria_id, sku, nombre, descripcion, precio, imagen) VALUES
(@cat_mujer, 'TF-REM-M01', 'Remera blanca de mujer', 'Remera blanca con escote en V.', 22900.00, 'clase5/imagenes/remera-mujer.png');

INSERT INTO productos (categoria_id, sku, nombre, descripcion, precio, imagen) VALUES
(@cat_infantil, 'TF-REM-N01', 'Remera blanca infantil', 'Remera infantil de manga larga.', 16900.00, 'clase5/imagenes/remera-infantil.png');

INSERT INTO productos (categoria_id, sku, nombre, descripcion, precio, imagen) VALUES
(@cat_accesorios, 'TF-GOR-01', 'Gorra', 'Accesorio para completar el conjunto.', 14900.00, 'imagenes/gorras.png');

SET @cliente = (SELECT id FROM usuarios WHERE email = 'lucas.medina@example.com');
SET @remera_hombre = (SELECT id FROM productos WHERE sku = 'TF-REM-H01');
SET @remera_infantil = (SELECT id FROM productos WHERE sku = 'TF-REM-N01');

INSERT INTO pedidos (codigo, usuario_id, estado_id)
VALUES ('TF-1001', @cliente, @estado_pendiente);
SET @pedido = LAST_INSERT_ID();

INSERT INTO detalle_pedidos (pedido_id, producto_id, talle, cantidad, precio_unitario) VALUES
(@pedido, @remera_hombre, 'M', 1, 24900.00),
(@pedido, @remera_infantil, '8', 2, 16900.00);

COMMIT;

SELECT id, nombre, precio
FROM productos
WHERE activo = 1 AND precio <= 25000.00
ORDER BY precio ASC, nombre ASC;

SELECT p.nombre AS producto, c.nombre AS categoria, p.precio
FROM productos AS p
INNER JOIN categorias AS c ON p.categoria_id = c.id
WHERE p.activo = 1
ORDER BY c.nombre ASC, p.nombre ASC;

SELECT pe.codigo, u.nombre AS cliente, e.nombre AS estado,
       pr.nombre AS producto, d.talle, d.cantidad, d.precio_unitario,
       d.cantidad * d.precio_unitario AS subtotal
FROM detalle_pedidos AS d
INNER JOIN pedidos AS pe ON d.pedido_id = pe.id
INNER JOIN usuarios AS u ON pe.usuario_id = u.id
INNER JOIN estados_pedido AS e ON pe.estado_id = e.id
INNER JOIN productos AS pr ON d.producto_id = pr.id
WHERE pe.codigo = 'TF-1001'
ORDER BY d.id ASC;

SELECT pe.codigo, SUM(d.cantidad * d.precio_unitario) AS total
FROM pedidos AS pe
INNER JOIN detalle_pedidos AS d ON d.pedido_id = pe.id
WHERE pe.codigo = 'TF-1001'
GROUP BY pe.id, pe.codigo;

UPDATE productos
SET precio = 25900.00
WHERE sku = 'TF-REM-H01';

SELECT sku, nombre, precio
FROM productos
WHERE sku = 'TF-REM-H01';

INSERT INTO productos (categoria_id, sku, nombre, precio)
VALUES (@cat_accesorios, 'TP6-BORRAR', 'Producto temporal de prueba', 1000.00);

DELETE FROM productos
WHERE sku = 'TP6-BORRAR';

SELECT COUNT(*) AS productos_temporales_restantes
FROM productos
WHERE sku = 'TP6-BORRAR';
