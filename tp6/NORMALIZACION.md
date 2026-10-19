# TP6 - Diseño y creación de la base de datos de TF Clothing

## Proyecto

TF Clothing es mi proyecto de una tienda de ropa para hombre, mujer y niño/a, con una sección de accesorios. En este trabajo organicé los datos de los usuarios, los productos y los pedidos.

Los nombres de las personas son ficticios. Las prendas pertenecen al catálogo de la página y los precios de prueba están expresados en pesos argentinos.

Este TP crea y prueba la base de datos. La página todavía usa su catálogo PHP y su carrito de sesión; la conexión con MySQL mediante PDO corresponde al TP7.

# Parte 1. Normalización

## Paso 1. Registro sin normalizar

Elegí un pedido como ejemplo. Lucas Medina compra una remera azul de hombre, talle M, y dos remeras blancas infantiles, talle 8. El pedido tiene el código TF-1001.

Al principio podría guardar todos los datos del pedido en una sola fila. Para que se lea sin una tabla demasiado ancha, muestro esa misma fila en forma de ficha:

| Campo | Valor |
| --- | --- |
| pedido_id | 1 |
| codigo | TF-1001 |
| usuario_id | 1 |
| nombre | Lucas |
| apellido | Medina |
| email | lucas.medina@example.com |
| rol_id | 1 |
| rol_nombre | cliente |
| estado_id | 1 |
| estado_nombre | pendiente |
| producto_ids | 1, 3 |
| producto_nombres | Remera azul de hombre, Remera blanca infantil |
| categoria_ids | 1, 3 |
| categoria_nombres | Hombre, Niño/a |
| talles | M, 8 |
| cantidades | 1, 2 |
| precios_actuales | 24900.00, 16900.00 |
| precios_unitarios | 24900.00, 16900.00 |

### Que problema tiene

Las columnas de productos, talles, cantidades y precios contienen listas. Para saber qué precio corresponde a cada prenda, tendría que respetar el orden de esas listas.

Además, si Lucas hiciera otro pedido, volvería a guardar su nombre y correo. También repetiría los nombres de las categorías. Eso puede dejar datos distintos para la misma persona o categoría si se modifica una fila y se olvida otra.

## Paso 2. Primera forma normal (1FN)

### Que cambie

Quité las listas y dejé una fila por producto y talle. Así, cada campo contiene un solo valor.

La tabla completa de este paso tiene estas columnas:

```text
ventas_1fn
  pedido_id, codigo,
  usuario_id, nombre, apellido, email, rol_id, rol_nombre,
  estado_id, estado_nombre,
  producto_id, producto_nombre, categoria_id, categoria_nombre,
  precio_actual, talle, cantidad, precio_unitario
```

La clave de esta tabla es la combinación de pedido_id, producto_id y talle. Dentro de un pedido, un mismo producto y talle ocupa una sola fila, con su cantidad y su precio de venta. Si se compran más unidades, se aumenta la cantidad.

### Como queda el ejemplo

Estos bloques muestran las columnas de las mismas dos filas. Se dividen solo para poder leerlas; todavía no son tablas separadas.

**Columnas del pedido y de la compra**

| pedido_id | producto_id | talle | codigo | cantidad | precio_unitario |
| --- | --- | --- | --- | --- | --- |
| 1 | 1 | M | TF-1001 | 1 | 24900.00 |
| 1 | 3 | 8 | TF-1001 | 2 | 16900.00 |

**Columnas del cliente y del estado, repetidas en las dos filas**

| Dato | Fila: producto 1, talle M | Fila: producto 3, talle 8 |
| --- | --- | --- |
| usuario_id | 1 | 1 |
| nombre | Lucas | Lucas |
| apellido | Medina | Medina |
| email | lucas.medina@example.com | lucas.medina@example.com |
| rol_id | 1 | 1 |
| rol_nombre | cliente | cliente |
| estado_id | 1 | 1 |
| estado_nombre | pendiente | pendiente |

**Columnas de los productos**

| Dato | Fila: producto 1, talle M | Fila: producto 3, talle 8 |
| --- | --- | --- |
| producto_nombre | Remera azul de hombre | Remera blanca infantil |
| categoria_id | 1 | 3 |
| categoria_nombre | Hombre | Niño/a |
| precio_actual | 24900.00 | 16900.00 |

Ya no hay listas en las celdas. Todavía se repiten los datos del cliente y del pedido, por eso sigo con 2FN.

## Paso 3. Segunda forma normal (2FN)

### Que revisé

En la tabla anterior, la clave tiene tres campos. Revisé cuáles datos dependen de toda la clave y cuáles dependen solo de una parte.

| Dependencia | Datos que determina |
| --- | --- |
| pedido_id | codigo, usuario_id, nombre, apellido, email, rol_id, rol_nombre, estado_id, estado_nombre |
| producto_id | producto_nombre, categoria_id, categoria_nombre, precio_actual |
| pedido_id + producto_id + talle | cantidad, precio_unitario |

Los datos del pedido dependen solo de pedido_id. Los datos del producto dependen solo de producto_id. Esas son dependencias parciales, porque no necesitan la clave completa.

### Que separé

Creé tres tablas intermedias. Sus nombres terminan en _2fn para distinguir este paso del esquema final.

**pedidos_2fn**

```text
PK: pedido_id
Campos: codigo, usuario_id, nombre, apellido, email,
        rol_id, rol_nombre, estado_id, estado_nombre
```

| pedido_id | codigo | usuario_id | estado_id |
| --- | --- | --- | --- |
| 1 | TF-1001 | 1 | 1 |

En esa única fila también están Lucas, Medina, su correo, el rol cliente y el estado pendiente. Ya no se repiten por cada producto.

**productos_2fn**

```text
PK: producto_id
Campos: producto_nombre, categoria_id, categoria_nombre, precio_actual
```

| producto_id | producto_nombre | categoria_id | categoria_nombre | precio_actual |
| --- | --- | --- | --- | --- |
| 1 | Remera azul de hombre | 1 | Hombre | 24900.00 |
| 3 | Remera blanca infantil | 3 | Niño/a | 16900.00 |

**detalle_pedidos_2fn**

```text
PK compuesta: pedido_id, producto_id, talle
FK: pedido_id -> pedidos_2fn.pedido_id
FK: producto_id -> productos_2fn.producto_id
Campos restantes: cantidad, precio_unitario
```

| pedido_id | producto_id | talle | cantidad | precio_unitario |
| --- | --- | --- | --- | --- |
| 1 | 1 | M | 1 | 24900.00 |
| 1 | 3 | 8 | 2 | 16900.00 |

Ahora cada grupo está en su tabla. Sin embargo, si el cliente hace más pedidos, sus datos personales todavía se repetirían. También se repetirían los nombres de categorías y estados.

## Paso 4. Tercera forma normal (3FN)

### Que revisé

Busqué datos que dependían de otro campo que no era la clave primaria de su tabla.

| Cadena de dependencia | Tabla que separé |
| --- | --- |
| pedido_id -> usuario_id -> nombre, apellido, email, rol_id | usuarios |
| usuario_id -> rol_id -> rol_nombre | roles |
| pedido_id -> estado_id -> estado_nombre | estados_pedido |
| producto_id -> categoria_id -> categoria_nombre | categorias |

Por ejemplo, el nombre Hombre depende de categoria_id, no directamente del producto. Si varios productos pertenecen a Hombre, no hace falta escribir ese nombre en cada uno.

### Que cambié

- Pasé los datos personales a usuarios. Cada pedido guarda usuario_id.
- Pasé los nombres de los roles a roles. Cada usuario guarda rol_id.
- Pasé los nombres de las categorías a categorias. Cada producto guarda categoria_id.
- Pasé los nombres de los estados a estados_pedido. Cada pedido guarda estado_id.

Así, para cambiar el correo de Lucas, se modifica una sola fila en usuarios. Para cambiar el nombre de una categoría, se modifica una sola fila en categorias.

## Paso 5. Listado final de tablas, PK y FK

PK identifica una fila. FK relaciona una fila con la clave de otra tabla.

| Tabla | Clave primaria (PK) | Claves foráneas (FK) |
| --- | --- | --- |
| roles | id | No tiene |
| usuarios | id | rol_id -> roles.id |
| categorias | id | No tiene |
| productos | id | categoria_id -> categorias.id |
| estados_pedido | id | No tiene |
| pedidos | id | usuario_id -> usuarios.id; estado_id -> estados_pedido.id |
| detalle_pedidos | id | pedido_id -> pedidos.id; producto_id -> productos.id |

### Campos de cada tabla

| Tabla | Otros campos |
| --- | --- |
| roles | nombre |
| usuarios | rol_id, nombre, apellido, email, password |
| categorias | nombre |
| productos | categoria_id, sku, nombre, descripcion, precio, imagen, activo |
| estados_pedido | nombre |
| pedidos | codigo, usuario_id, estado_id |
| detalle_pedidos | pedido_id, producto_id, talle, cantidad, precio_unitario |

Todas las tablas también tienen created_at y updated_at.

En el SQL final usé id autoincremental como PK en todas las tablas, como pide la consigna. En detalle_pedidos conservé UNIQUE(pedido_id, producto_id, talle) para que esa combinación no se repita. Agregar un id no reemplaza la revisión de la clave compuesta que hice en 2FN.

### Relaciones

- Un rol puede tener varios usuarios.
- Un usuario puede realizar varios pedidos.
- Una categoría puede tener varios productos.
- Un estado puede corresponder a varios pedidos.
- Un pedido puede tener varios detalles.
- Un producto puede aparecer en detalles de distintos pedidos.

Pedidos y productos tienen una relación muchos a muchos. La tabla detalle_pedidos la resuelve guardando cada producto comprado, su talle, su cantidad y su precio de venta.

## Paso 6. Datos agregados para usar la base en la tienda

Después de separar las tablas agregué los campos que necesita el proyecto:

- En usuarios, password permite guardar la contraseña protegida con un hash.
- En productos, sku es un código único de producto. También agregué descripción, imagen y activo.
- En pedidos, codigo identifica el pedido con un texto como TF-1001.
- created_at guarda la fecha de creación y updated_at la última modificación.

El precio de productos es el precio actual. El precio_unitario del detalle es el precio de esa compra y no se cambia si después aumenta el catálogo. El total se obtiene sumando cantidad por precio_unitario; no lo guardé en otra columna.

En este esquema cada usuario tiene un solo rol y cada producto pertenece a una sola categoría. Los talles se guardan en el detalle como un solo valor por fila. Este esquema no incluye una tabla de variantes ni stock por talle; esos datos necesitarían ampliarse si la tienda los gestionara desde la base. Los pagos y los envíos no forman parte de este TP.

# Parte 2. Creación del esquema en MySQL

El archivo de esta parte es schema.sql.

## Paso 1. Crear y seleccionar la base

Se usa CREATE DATABASE IF NOT EXISTS tf_tienda_tp6 y después USE tf_tienda_tp6.

## Paso 2. Crear las tablas

Primero se crean roles, categorias y estados_pedido. Después se crean las tablas que las referencian. Cada PK se define con INT AUTO_INCREMENT PRIMARY KEY.

Usuarios incluye nombre, apellido, email, password y rol_id. Productos y pedidos representan el negocio. Detalle_pedidos relaciona pedidos y productos.

## Paso 3. Agregar las relaciones y restricciones

Las claves foráneas se definen con FOREIGN KEY y REFERENCES. Impiden guardar una referencia a un registro que no existe.

Email, sku y codigo de pedido son únicos. Cantidad debe ser mayor que cero y los precios no pueden ser negativos. Los precios usan DECIMAL(10,2).

No se puede borrar una categoría que tenga productos ni un producto que ya aparezca en un pedido. Si se borra un pedido, sus detalles se borran con él.

## Paso 4. Agregar auditoría e índice

Todas las tablas incluyen:

```sql
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

El índice explícito es:

```sql
CREATE INDEX idx_productos_categoria_activo
ON productos(categoria_id, activo);
```

Elegí esos campos porque la página organiza las prendas por categoría y permite distinguir los productos activos.

# Parte 3. Consultas de prueba CRUD

El archivo de esta parte es consultas.sql. Las operaciones están en este orden:

## Paso 1. CREATE: insertar datos

- Dos roles: cliente y administrador.
- Cuatro categorías: Hombre, Mujer, Niño/a y Accesorios.
- Tres estados: pendiente, confirmado y cancelado.
- Dos usuarios: Lucas Medina y Martina Ríos.
- Cuatro productos del catálogo, cada uno relacionado con su categoría.
- Un pedido de Lucas con dos filas en detalle_pedidos.

El pedido TF-1001 tiene una remera de hombre de $24.900 y dos infantiles de $16.900. Total: $58.700.

## Paso 2. READ: consultar datos

Una consulta usa WHERE para mostrar productos activos de hasta $25.000 y ORDER BY para ordenarlos por precio.

Otra usa INNER JOIN para mostrar el nombre del producto y el de su categoría. También hay una consulta que relaciona el pedido, su cliente, el estado y sus productos, y otra que calcula el total.

## Paso 3. UPDATE: modificar un registro

La remera azul cambia de $24.900 a $25.900. La condición WHERE usa su código único, TF-REM-H01. El precio guardado en el pedido sigue siendo $24.900.

## Paso 4. DELETE: eliminar un registro de prueba

Primero se crea un producto temporal con el código TP6-BORRAR. Después se elimina solo ese registro usando WHERE. La consulta final comprueba que queden cero filas con ese código.
