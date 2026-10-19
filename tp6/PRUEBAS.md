# Prueba local del TP6

Fecha: 19/10/2026.

Motor utilizado: MariaDB 10.4.32, incluido en XAMPP.

## 1. Ejecución de los archivos

Se ejecutó schema.sql completo en una base vacía. Después se ejecutó consultas.sql completo en la misma conexión para conservar sus variables. Ambos terminaron sin errores.

La prueba final se hizo en una base temporal, con los mismos archivos y cambiando únicamente el nombre de la base. Al terminar se retiró esa base. La base local tf_tienda_tp6 se conservó.

## 2. Comprobaciones del esquema

| Punto | Resultado |
| --- | --- |
| Tablas | 7 |
| PK autoincrementales | Una por tabla |
| Claves foráneas | 6 |
| created_at y updated_at | Presentes en las 7 tablas |
| Índice explícito | idx_productos_categoria_activo |

## 3. Comprobaciones de los datos y del CRUD

| Operación | Resultado |
| --- | --- |
| Insertar roles | 2 |
| Insertar categorías | 4 |
| Insertar estados | 3 |
| Insertar usuarios | Lucas Medina y Martina Ríos |
| Insertar productos vinculados a categorías | 4 |
| Insertar pedido y detalles | 1 pedido y 2 líneas |
| SELECT con WHERE y ORDER BY | 4 productos activos de hasta $25.000, ordenados por precio antes del UPDATE |
| SELECT con INNER JOIN | Productos con categoría y pedido con cliente, estado y prendas |
| Total del pedido TF-1001 | 58700.00 |
| UPDATE con WHERE | La remera azul quedó en 25900.00 |
| Precio de esa remera en el pedido | Se conservó en 24900.00 |
| DELETE con WHERE | El producto temporal dejó de existir; conteo final 0 |

## 4. Pruebas de las restricciones

Estas operaciones se probaron aparte de consultas.sql para comprobar que la base rechace datos incorrectos.

| Intento | Resultado |
| --- | --- |
| Insertar un producto con categoría inexistente | Rechazado por FK |
| Borrar una categoría que tiene productos | Rechazado por FK |
| Repetir el correo de Lucas | Rechazado por UNIQUE |
| Repetir producto y talle dentro del mismo pedido | Rechazado por UNIQUE |
| Guardar cantidad cero | Rechazado por CHECK |
| Guardar precio negativo | Rechazado por CHECK |
| Borrar un pedido | Se borraron sus detalles; luego se revirtió la prueba con ROLLBACK |

Las operaciones rechazadas no forman parte del recorrido normal de importación de los archivos entregados.
