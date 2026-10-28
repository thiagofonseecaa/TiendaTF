USE tf_tienda_tp6;

-- Consulta de comprobación: no borra ni modifica los datos del TP6.
SHOW COLUMNS FROM usuarios;
SHOW INDEX FROM usuarios;

-- Equivalencia con el campo rol de la estructura recomendada en la consigna.
-- En TF el rol se obtiene de la tabla relacionada, sin duplicarlo en usuarios.
SELECT u.id, u.nombre, u.apellido, u.email,
       CASE r.nombre WHEN 'administrador' THEN 'admin' WHEN 'cliente' THEN 'usuario' END AS rol,
       u.created_at
FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id
ORDER BY u.id;
